<?php
    $sid = $_GET['sid'] ?? null;
    if (!$sid) {
        header("Location: http://localhost/dashboard/Microfinance/landing_page/public/login");
        exit;
    };
    
    $ch = curl_init("http://localhost/dashboard/Microfinance/landing_page/public/api/fetch-token?sid=" . urlencode($sid));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    $token = $data['token'] ?? null;
    
    if (!$token) {
        header("Location: http://localhost/dashboard/Microfinance/landing_page/public/login");
        exit;
    }
    echo $token;

    $ch = curl_init("http://localhost/dashboard/Microfinance/landing_page/public/api/user");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "Accept: application/json"
    ]);

    $response = curl_exec($ch);

    //echo $response;
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code !== 200) {
        header("Location: http://localhost/dashboard/Microfinance/landing_page/public/login");
        exit;
    }
    

    $user = json_decode($response, true);
    echo $response;

    /*you can now use $user values as values to the $_SESSION global var in php
    *   $_SESSION['fullname'];
    */
    //logout
    function logout($sid, $token) {
        $data = json_encode(['sid' => $sid]);
        $ch = curl_init("http://localhost/dashboard/Microfinance/landing_page/public/api/logout");
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            "Authorization: Bearer $token"
        ]);
    
        $response = json_decode(curl_exec($ch),true);
        curl_close($ch);

        if($response['status'] == 200){
            header("Location: http://localhost/dashboard/Microfinance/landing_page/public/login");
            exit;
        };
    }

    if(isset($_POST['logout'])){
        
        logout($sid, $token);
    }

?>
