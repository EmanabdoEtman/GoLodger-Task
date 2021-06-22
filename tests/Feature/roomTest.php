<?php


namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase; 
use App\Http\Repositories\GitRoom;
use Tests\TestCase;

class roomTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    
    /** @test */  
    public function link_return_array()
    { 
        $room = new GitRoom();
        $links = $room->getApiLinks();   
        foreach ($links as $link) {
            $this->assertIsArray($room->getApiData($link)); 
         }
  
    } 
 

 

    
}
