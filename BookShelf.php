<?php

namespace App\Library;

class BookShelf{
    //maximum capacity set to 50
    public $maximumCapacity = 50;
    /**************************************************************************************************************
    * check capacity of items bookshelf can hold for each item
    *************************************************************************************************************/
    public function isCapacityMaxedOut(): bool {
        try {
            $bookshelf =  json_decode(file_get_contents("./bookshelf.json"));
            return (int) $bookshelf[3]->capacity >= $this->maximumCapacity ? true: false;
        } catch (\Exception $exception) {
            return true;
        }
    }

    /**************************************************************************************************************
    * Validation goes on here => Validation Principles
    * Book Accessor = title and author
    * Magazine Accessor = name
    * Notebook Accessor = owner
    * Using if else sounds cliche but => brevity and timeframe requires this workflow
    *************************************************************************************************************/
    public function validateAndStore(array $payload): ?bool {
        try {
            //Books validation has two params
            if(isset($payload['title']) && isset($payload['author']) && sizeof($payload) === 2){
                return $this->saveBooks($payload);
            }else if(isset($payload['name']) && sizeof($payload) === 1){
                //magazine params requires one param and param = name
                return $this->saveBooks($payload);
            }else if(isset($payload['owner']) && sizeof($payload) === 1){
                //notebook params requires one param and param = name
                return $this->saveBooks($payload);
            }else{
                return false;
            }
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**************************************************************************************************************
    * Saves data depending on param to its respective item type => Books, notebook, or magazine
    *************************************************************************************************************/
    private function saveBooks(array $payload): ?bool {
        try {
            //first get bookshelf data from file
            $bookshelf =  json_decode(file_get_contents("bookshelf.json"), true);

            //get type of item based on the accessor
            $type = (isset($payload['title']) && isset($payload['author']) && sizeof($payload) === 2) ?  'books': ( isset($payload['name'])  ? 'magazine': 'notebook' );
            //get the index of array object based on accessor
            $index = (isset($payload['title']) && isset($payload['author']) && sizeof($payload) === 2) ?  0: ( isset($payload['name'])  ? 1: 2 );

            //check if array key already exists => avoid duplicity => not efficient requires code refactoring
            if(in_array($bookshelf[$index][$type], $payload)){
                return false;
            }
            
            //get new payload and assign to new array in exisiting data file => if file[] = 1 append file[] = 2 hence now array is [1, 2]
            $bookshelf[$index][$type][] = $payload;
            
            //save data to file => dealign with JSON
            file_put_contents("./bookshelf.json", json_encode($bookshelf, JSON_PRETTY_PRINT));
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**************************************************************************************************************
    * Retrieves all states => first gets by offset and limit hence page limits can be exercised
    *************************************************************************************************************/
    public function getState(int $min, int $max): ?object {
        try {
            $bookshelf =  [
                'item_total'=> $this->itemsTotal(), 
                'item_remaining_capacity'=> $this->itemsRemainingCapacity() , 
                'items'=> $this->getItems($min, $max)  
            ];
            return (object) $bookshelf;
        } catch (\Exception $exception) {
            return (object) ['error'=>$exception->getMessage()];
        }
    }

    /**************************************************************************************************************
    * gets each items total current number of values/entries 
    *************************************************************************************************************/
    private function itemsTotal(): object{
        try {
            $bookshelf = json_decode(file_get_contents("./bookshelf.json"));
            return (object) [
                'books'=> (int) sizeof($bookshelf[0]->books), 
                'magazine'=> (int) sizeof($bookshelf[1]->magazine), 
                'notebook'=>(int) sizeof($bookshelf[2]->notebook) 
            ];
        } catch (\Exception $exception) {
            return (object) [];
        }
    }
    

    /**************************************************************************************************************
    * Gets each item remaining based on the maximum capacity => If capacity is 50 and entries are 47 it means
    * only 3 entries are left to fill it to the brim => Got to alert the user who tries after capacity is full
    *************************************************************************************************************/
    private function itemsRemainingCapacity(): Object{
        try {
            $bookshelf =  json_decode(file_get_contents("./bookshelf.json"));
            return (object) [
                'books'=> ($this->maximumCapacity - sizeof($bookshelf[0]->books)), 
                'magazine'=>($this->maximumCapacity - sizeof($bookshelf[1]->magazine)), 
                'notebook'=>($this->maximumCapacity - sizeof($bookshelf[2]->notebook))
            ];
            //return (object) ['book'=>400, 'magazine'=> 500, 'notebook'=> 100, '_sizeof'=> sizeof($bookshelf[0]->books) ];
        } catch (\Exception $exception) {
            return (object) ['error'=>$exception->getMessage()."".$exception->getLine()];
        }
    }

    /**************************************************************************************************************
    * Now let us get all the items values from the file and present this data so it makes sense
    *************************************************************************************************************/
    private function getItems(int $min, int $max): Object{
        try {
            $bookshelf =  json_decode(file_get_contents("./bookshelf.json"));
            /*************************************************************************************************************************************
            /* Applying array slice ensures offsets and limits are placed on files that should be retrieved from db
            * Perhaps code refactoring ought to be done here to prevent potential memory leaks
            *************************************************************************************************************************************/
            return (object) ['books'=>  array_slice($bookshelf[0]->books, $min, $max), 'magazine'=> array_slice($bookshelf[1]->magazine, $min, $max), 'notebook'=> array_slice($bookshelf[2]->notebook, $min, $max) ];
        } catch (\Exception $exception) {
            return (object) ['error'=>$exception->getMessage()."".$exception->getLine()];
        }
    }

}