<?php

namespace App\Http\Controllers;

use App\testing;
use Illuminate\Http\Request;
use App\testModel;
use Illuminate\Database\Eloquent\Model;
//use DB;
use App\multipleSelectModel;
use Doctrine\DBAL\Driver\PDOConnection;
use Illuminate\Support\Facades\DB;
use PDO;
class TestingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function test()
    {
        $pdo = DB::connection('mysql')->getPdo();
        $hi = 'hellow dear';
        $stmt = $pdo->prepare("CALL test( :param1 , @hff );");


        //$stmt->bindParam(1, 'hellow');
        $stmt -> bindValue(':param1', 'John');
        
        //$stmt->bindParam(2, $return_value, \PDO::PARAM_STR, 4000); 

// call the stored procedure
        $stmt->execute();

        //print "procedure returned $return_value\n";
        $res = DB::select("select @hff as hi");


        return $res;

/*        $stmt = $pdo->prepare("call test(?)");
        $value = 'hello';
        $stmt->bindParam(1, $value, \PDO::PARAM_STR|\PDO::PARAM_INPUT_OUTPUT, 4000); 

// call the stored procedure
        $stmt->execute();

        print "procedure returned $value\n";

*/




        /*$stmt = $pdo->prepare('call test(?)',[\PDO::ATTR_CURSOR=>\PDO::CURSOR_SCROLL]);

        $stmt->bindParam(1, $return_value, \PDO::PARAM_STR, 4000);
        $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
        $exec = $stmt->execute();
        if (!$exec) return $pdo->errorInfo();
        //if ($isExecute) return $exec;
        echo $return_value;*/



        /*$pdo = DB::connection('mysql')->getPdo();
        $stmt = $pdo->prepare("CALL test(?)");
        $stmt->bindParam(1, $return_value, \PDO::PARAM_STR, 4000); */

        // call the stored procedure
        //$stmt->execute();
        //$result[] =  $stmt->fetchAll(\PDO::FETCH_OBJ);
        //print "procedure returned $return_value\n";
        //print_r($return_value);

        //$d = testModel::testDB();
        //echo $d;

        //return redirect('f1')->withR1('this is R1');
    }

    public function test2()
    {
        return session('r1');
    }






    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\testing  $testing
     * @return \Illuminate\Http\Response
     */
    public function show(testing $testing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\testing  $testing
     * @return \Illuminate\Http\Response
     */
    public function edit(testing $testing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\testing  $testing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, testing $testing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\testing  $testing
     * @return \Illuminate\Http\Response
     */
    public function destroy(testing $testing)
    {
        //
    }
}
