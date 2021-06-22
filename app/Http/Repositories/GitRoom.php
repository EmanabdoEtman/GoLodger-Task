<?php 
namespace App\Http\Repositories;
 
use App\Http\Resources\GetDataResource;

class GitRoom implements GitRoomInterface
{ 
    protected $links;

	public function __construct()
	{ 
    }
 
   #get Api Links 
   # We can get it from many ways (database , get , static ....)
    public function getApiLinks()
    {
        $this->links[0] = 'https://f704cb9e-bf27-440c-a927-4c8e57e3bad1.mock.pstmn.io/s1/availability';
        $this->links[1] = 'https://f704cb9e-bf27-440c-a927-4c8e57e3bad1.mock.pstmn.io/s2/availability';
        
        return $this->links;
    }
    /**
     * get api link data  
    */ 
    public function getApiData($link)
    {  
            #use curl to get data
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($ch, CURLOPT_URL, $link); 
            $result = curl_exec($ch); 
            curl_close($ch);

            $ApiData = json_decode($result, true);
            
            if (isset($ApiData['error'])) {
                $ApiLinkData = [];
            } else {
                $ApiLinkData = $ApiData;
            }  
         return $ApiLinkData;
    }

    
    /**
     * get hotel rooms  
    */ 
    public function getHotelRoom($allHotel)
    { 
        #get hotel rooms and rename array index
        foreach ($allHotel  as $hotelData) {
            $room['Hotel']=$hotelData['name'];
            $room['Stars']=$hotelData['stars'];
            foreach ($hotelData['rooms']  as $roomData) { 
                $room['Code']=$roomData['code'];
                $room['RoomName']=(isset($roomData['name']) ? $roomData['name'] : "Not Defined");
                $room['NetPrice']=(isset($roomData['net_price']) ? $roomData['net_price'] : $roomData['net_rate']);
                $room['TotalPrice']=(isset($roomData['totalPrice']) ? $roomData['totalPrice'] : $roomData['total']); 
                $room['Taxes']=(isset($roomData['taxes']) ? $this->calculateTaxes($roomData['taxes']) : 0); 
                $Rooms[] = $room;
                
            }  
        }
               return $Rooms;

    }


      /**
     * calculate total taxes
     */
    public function calculateTaxes(array $roomTaxes): float
    {
        if (isset($roomTaxes['amount'])) {
            return (float)$roomTaxes['amount'];
        }
        $roomTaxesVal = 0;
        foreach ($roomTaxes as $tax) {
            $taxVal = (float)$tax['amount'];
            $roomTaxesVal += $taxVal;
        }
        return $roomTaxesVal;
    }

      /**
     * Get rooms in descending order by price
     */
    public function getRoomsSorting(array $rooms): array
    { 
        $roomsData = [];
        foreach ($rooms as $roomItem) { 
            $hotelRoomCode = $roomItem['Hotel'].'-'.$roomItem['Code']; 
            $roomCode = $roomItem['Code'];  
            $roomItemData['roomCode'] = $roomItem['Code'];
            $roomItemData['roomHotel'] = $roomItem['Hotel']; 
            $roomItemData['RoomStars'] = $roomItem['Stars']; 
            $roomItemData['RoomName'] = $roomItem['RoomName']; 
            $roomItemData['roomNetPrice'] = $roomItem['NetPrice']; 
            $roomItemData['roomTotalPrice'] = $roomItem['TotalPrice']; 
            $roomItemData['roomTaxes'] = $roomItem['Taxes'];  

            if(array_key_exists($hotelRoomCode, $roomsData) && ($roomItem['TotalPrice'] < $roomsData[$hotelRoomCode]['roomTotalPrice'] ))
            {
                unset($roomsData[$hotelRoomCode]);            
                $roomsData[$hotelRoomCode] = $roomItemData;
            }elseif(!array_key_exists($hotelRoomCode, $roomsData)) {       
                $roomsData[$hotelRoomCode] = $roomItemData;
            } 
        }
 

         usort($roomsData, function ($a, $b) {
            if ($a['roomTotalPrice'] == $b['roomTotalPrice']) return 0;
            return $a['roomTotalPrice'] > $b['roomTotalPrice'] ? 1 : -1;
        }); 
        return $roomsData;
    }


  
}