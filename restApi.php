<?php
/*************************************************************************************************************************************      
  TASK =>
    * Breakdown => create 2 classes and lets apply some OOP 
    * Define the classes and their methods (including parameters and return types) for a system that consist of 
    1. a bookshelf, 
    2. books, 
    3. magazines
    4. notebooks. 

    ** Algo Capabilities **

    The bookshelf should 
    1. allow store of the items
    2. retrieval of the items 
    3. reporting on the state of the bookshelf 
        a. how many items it has, 
        b. how many more items it * can hold)
        c. initializing the capacity (in number of items it can hold in total). 
    4. The other items should allow 
    a. (reading) of a single page given the page number * that returns the text of the page. 

    DATASET PARAMS
    A book has an accessible title and author. 
    A magazine has an accessible name. 
    A notebook has an accessible owner. 
************************************************************************************************************************************************************/

namespace App\Library;

//Adding other class 
require_once("./BookShelf.php");

use App\Library\BookShelf;

class restApi extends BookShelf{

    public function __construct(){
        $this->cors();
    }

    /**************************************************************************************************************
    * CORS SETUP FOR FUTURE REQUESTS FROM Any Origin
    *************************************************************************************************************/
    private function cors(): bool{
        try {
           
            /**************************************
            * Adding Cors 
            *******************************/
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Credentials: true always');
            header('Access-Control-Allow-Headers: true always');
            header('Access-Control-Max-Age: 3');
            header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');
            header('Cache-Control: max-age=0');
            header('Cache-Control: no-cache');
            header('Cache-Control: no-store');
            header('Cache-Control: public');
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**************************************************************************************************************
    * Store datasets
    *************************************************************************************************************/
    public function store(array $payload): string {
        try {
            //check if capacity is full
            $isCapacityFull = $this->isCapacityMaxedOut();
            if(!$isCapacityFull)return $this->json(['status'=>false, 'data'=>[], 'error'=>"Sorry bookshelf capacity has maxed out\nPlease try again later when its free"]);

            //validate and store
            $isStored = $this->validateAndStore($payload);
            if(!$isStored)return $this->json(['status'=>false, 'data'=>[], 'error'=>"Sorry could not store data"]);


            //get stored current data
            $bookShelfState = $this->getState(0, 1);

            return $this->json(['status'=>true, 'data'=>$bookShelfState, 'error'=>null]);
        } catch (\Exception $exception) {
            return $this->json(['status'=>false, 'data'=>[], 'error'=>$exception->getMessage()." @ ".$exception->getLine()]);
        }
    }

    /**************************************************************************************************************
    * Get All Items from our bookshelf
    *************************************************************************************************************/
    public function get(int $min, int $max): string{
        try {
            //get stored current data
            $bookShelfState = $this->getState($min, $max);

            return $this->json(['status'=>true, 'data'=>[$bookShelfState], 'error'=>null]);
        } catch (\Exception $exception) {
            return (string) ['error'=>$exception->getMessage()." @ line ".$exception->getLine()." @ File ".$exception->getFile()];
        }
    }

    /**************************************************************************************************************
    * Attempt to mimic application/json responses
    *************************************************************************************************************/
    private function json($data): string{
        try {
             return (string) json_encode($data, JSON_PRETTY_PRINT);
        } catch (\Exception $exception) {
            return (string) json_encode('Fatal error @ '.$exception->getMessage()."".$exception->getLine(), JSON_PRETTY_PRINT);
        }
    }

}



function process($app){
    //get request method and setup routes
    $request = $_SERVER['REQUEST_METHOD'];
    switch($request){
        case "GET":
            //Ensures no matter what we have a default for offsets and limits
            $offset = isset($_GET['offset']) ? $_GET['offset']: 0;
            $limit = isset($_GET['limit']) ? $_GET['limit']: 100;
            $response = $app->get( intval($offset), intval($limit) );
            break;
        case "POST":
            //posts data already has validations
            $response =  $app->store($_POST);
            break;
        default:
            $response = json_encode(['status'=>'false']);
            break;
    }
    return $response;
}

//instatiate restApi
$app = new restApi;
print(process($app));
die(0);