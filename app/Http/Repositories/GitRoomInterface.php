<?php 

namespace App\Http\Repositories;

interface GitRoomInterface{ 

    public function getApiLinks();
    public function getApiData($link); 
    public function getHotelRoom($hotel); 
    public function getRoomsSorting(array $roomsData); 

}