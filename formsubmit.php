<?php
/**
 * Created by PhpStorm.
 * User: raj
 * Date: 16/09/2017
 * Time: 19:12
 */

/*echo '<pre>';
print_r($_POST);
echo '</pre>';*/
if($_POST)
{
    $postarr = array
    (
        "prod_sku" => $_POST['prod_sku'],
        "prod_name" => $_POST['prod_name'],
        "prod_desc" => $_POST['prod_desc'],
        "prod_price" => $_POST['prod_price'],
    );

    $filters = array
    (
        "prod_sku" => array
        (
            "filter"=>FILTER_CALLBACK,
            "flags"=>FILTER_FORCE_ARRAY,
            "options"=>"ucwords"
        ),
        "prod_name" => array
        (
            "filter"=>FILTER_CALLBACK,
            "flags"=>FILTER_FORCE_ARRAY,
            "options"=>"ucwords"
        ),
        "prod_desc" => array
        (
            "filter"=>FILTER_DEFAULT,
        ),
        "prod_price" => array
        (
            "filter"=>FILTER_VALIDATE_INT,
        ),
    );
    $postarr = filter_var_array($postarr, $filters);
    /*echo '<pre>';
    print_r($postarr);*/

    $products_array = array(
        "product"=>array(
            "title"=> $postarr['prod_name'],
            "body_html"=> $postarr['prod_desc'],
            "vendor"=> "Raj",
            "published"=> true ,
            "variants"=>array(
                array(
                    "sku"=>$postarr['prod_sku'],
                    "price"=>$postarr['prod_price'],
                    "taxable"=>false,
                )
            ),
            "images" => array(
                array(
                    "src" => $_POST['fileUploadPath']
                )
            )
        )
    );
    /*echo json_encode($products_array);
    echo "<br />";*/
    $url = "https://fe281f14d4d899ad3df0f2b8e1e451b1:8c8479ccc03827318ed73bd14faefe76@raj1976.myshopify.com/admin/products.json";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_VERBOSE, 0);
    curl_setopt($curl, CURLOPT_HEADER, 1);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($products_array));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec ($curl);
    curl_close ($curl);
    /*echo "<pre>";
    print_r($response);*/

    $response = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);
// Convert headers into an array
    $headers = array();
    $header_data = explode("\n",$response[0]);
    list(,,$stattext) = explode(" ",$header_data[0]);


    if(trim($stattext) == "Created")
    {
        echo "Product Created!";
    }
    else{
        echo "Product Not Created!";
    }
}
else
{
    die("Incorrect Url");
}

