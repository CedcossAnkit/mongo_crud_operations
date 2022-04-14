<?php

use Phalcon\Mvc\Controller;


class CrudController extends Controller
{
    public function indexAction()
    {
    }

    public function findUsersRecords($collectionName)
    {
        $response = $this->mongo->ankit->$collectionName->find();
        return $response;
    }

    public function homeAction()
    {
        $this->view->data = CrudController::findUsersRecords("test");
        if ($this->request->getPost("action") == "Save") { //for inserting data
            $name = $this->request->getPost("name");
            $password = $this->request->getPost("password");

            if ($name != "" && $password != "") {

                $SampleValueArr = [
                    "name" => $name,
                    "password" => $password
                ];

                $response = $this->mongo->ankit->test->insertOne($SampleValueArr);
            } else {
                die("all feilds are required");
            }
        }

        if ($this->request->getPost("delete")) {  //for delete the record
            $response = $this->mongo->ankit->test->deleteOne(["_id" => new MongoDB\BSON\ObjectId($this->request->getPost("delete"))]);
        }

        if ($this->request->getPost("edit")) { // for edit the record
            try {
                $response = $this->mongo->ankit->test->findOne(["_id" => new MongoDB\BSON\ObjectId($this->request->getPost("edit"))]);
                $this->view->editData = $response;
            } catch (Exception $e) {
            }
        }

        if ($this->request->getPost("action") == "Update") { //for update the record 
            $name = $this->request->getPost("name");
            $password = $this->request->getPost("password");
            $id = $this->request->getPost("id");
            $SampleUpdateValueArr = [
                "name" => $name,
                "password" => $password
            ];
            $updateResult = $this->mongo->ankit->test->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($id)],
                ['$set' => ['name' => $name, "password" => $password]]
            );
            die(print_r($updateResult));
        }
    }
}
