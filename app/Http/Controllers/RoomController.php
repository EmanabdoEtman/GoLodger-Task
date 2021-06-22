<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Http\Repositories\GitRoomInterface;

class RoomController extends Controller
{
    protected $room;
    protected $allHotel;
    protected $hotelData;

	public function __construct(GitRoomInterface $room)
	{
	   $this->room = $room;
	   $this->allHotel = []; 
       $this->hotelData = []; 
    } 


    
    public function getRoom(Request $request)
    {
        #get api links
        $links=$this->room->getApiLinks();

        #get hotels data from API links
        foreach ($links as $link) {
            $this->hotelData[] = $this->room->getApiData($link); 
         }
        #Store hotels data   
        if (count($this->hotelData) > 0) {
            foreach ($this->hotelData as $hotelDataItem) { 
                foreach ($hotelDataItem['hotels'] as $Item) { 
                    $this->allHotel[] = $Item; 
               }
            }
        }  
        #get rooms data   
        if (count($this->allHotel) > 0) {
            $Rooms = $this->room->getHotelRoom($this->allHotel);   
         } else { 
           echo json_encode(array('status' => 200, 'foundHotel' => false, 'rooms' => []));
       } 
       
        #remove duplicated rooms & get rooms sorting
        $roomSortedData = $this->room->getRoomsSorting($Rooms);   
        return $roomSortedData ;
    } 
}
