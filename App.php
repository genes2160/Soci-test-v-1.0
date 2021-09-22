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

class App extends BookShelf{

    public function __construct(){
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

//instatiate App
$app = new App;

print("********* STORAGE OF DATA COMMENCES HERE ************\n");
print("********* STORAGE OF BOOK ************\n");
print($app->store(['title'=>'Where the Wild Things Are', 'author'=>'Sendak the Caldecott'])."\n");
print($app->store(['title'=>'Charlie and the Chocolate Factory', 'author'=>'J.K. Rowling'])."\n");
print($app->store(['title'=>'Harry Potter and the Prisoner of Azhkaban', 'author'=>'J.K. Rowling'])."\n");
print($app->store(['title'=>'Harry Potter and the chamber of secrets', 'author'=>'J.K. Rowling'])."\n");
print($app->store(['title'=>'Harry Potter and the Goblet of Fire', 'author'=>'J.K. Rowling'])."\n");
print($app->store(['title'=>'Harry Potter and the Deathly Hallows', 'author'=>'J.K. Rowling'])."\n");
print("\n********* STORAGE OF BOOK VALIDATION CONFIRMATION ************\n");
//this is put here to confirm that validation works
print($app->store(['title'=>'Harry Potter and the Deathly Hallows', 'author'=>'J.K. Rowling', 'date_published'=> '2021-09-21 00:00:00'])."\n");

print("\n********* STORAGE OF MAGAZINE COMMENCES HERE ************\n");
//now lets save magazine data
print($app->store(['name'=>'Rogue Magazine'])."\n");
print("\n********* STORAGE OF MAGAZINE VALIDATION CONFIRMATION ************\n");
//magazine data validation confirmation
print($app->store(['name'=>'Rogue Magazine', 'date_published'=> '2021-09-21 00:00:00'])."\n");
print("\n********* STORAGE OF MAGAZINE ENDS HERE ************\n");
print("\n***************************************************************\n");


print("\n********* STORAGE OF NOTEBOOK COMMENCES HERE ************\n");
//notebook
print($app->store(['owner'=>'Eugene Dee'])."\n");
print($app->store(['owner'=>'Claire Duodu'])."\n");
print($app->store(['owner'=>'Charity Duodu'])."\n");
print("\n********* STORAGE OF NOTEBOOK VALIDATION CONFIRMATION ************\n");
//notebook validation confirmation
print($app->store(['owner'=>'Hannah Montana', 'reach'=>'Hannah Montana'])."\n");
print($app->store(['owner'=>'Hannah Montana', 'date_published'=>'2021-09-21 00:00:00'])."\n");
print("************************************************************************\n");
print("\n********* STORAGE OF NOTEBOOK ENDS HERE ************\n");
print("\n\n\n");


print("\n********* FETCHING ALL DATA STARTS HERE ************\n");
//get data endpoint
print("********* Getting data from 0 to 50 records ************\n");
print($app->get(0, 50)."\n");
print("************************************************************************");
print("\n********* FETCHING ALL DATA ENDS HERE ************\n");
print("\n********* GOODBYE - GUESS I AM DONE FOR NOW ************\n");
print("\n********* visit https://github.com/genes2160 just to have a gander, be warned nothing much happens there ************\n");
