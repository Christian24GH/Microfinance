<?php
    session_start();
    if(!isset($_SESSION['id'])){
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
        $_SESSION['token'] = $token;
    
        $ch = curl_init("http://localhost/dashboard/Microfinance/landing_page/public/api/user");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token",
            "Accept: application/json"
        ]);
    
        $response = curl_exec($ch);
    
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        if ($code !== 200) {
            header("Location: http://localhost/dashboard/Microfinance/landing_page/public/login");
            exit;
        }
        $user = json_decode($response, true);
        /*you can now use $user values as values to the $_SESSION global var in php
        *   $_SESSION['fullname'];
        */
        $_SESSION['role']=$user['role'];
        $_SESSION['email']=$user['email'];
        $_SESSION['id']=$user['id'];
        $_SESSION['fullname']=$user['fullname'];
        $_SESSION['sid'] = $sid;
        $_SESSION['token'] = $token;
    }

    function terminate(){
        header("Location: http://localhost/dashboard/Microfinance/landing_page/public/login");
        session_unset();
        session_destroy();
        exit;
    }
    //logout
    function logout($sid, $token) {
        
        if(!isset($sid)){
            echo "<script>alert('Missing Sid')</script>";
            terminate();
        }
        if(!isset($token)){
            echo "<script>alert('Missing Token')</script>";
            terminate();
        }
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
            terminate();  
            exit;
        };   
    }

    if(isset($_POST['logout'])){
        logout($_SESSION['sid'], $_SESSION['token']);
    }
?>
