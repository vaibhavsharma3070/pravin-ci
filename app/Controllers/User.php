<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\UserFilesModel;
use Twilio\Rest\Client;

class User extends BaseController
{

    //================== Index method =====================
    public function index(){
        $session = session();
        if($session->get('logged_in') == TRUE || $session->get('logged_in') == 1){
            // print_r($session->get());exit;
            $model = new UserModel();
            $data = $model->where('id', $session->get('user_id'))->first();
            if(isset($data)){
                if($data['role'] == 'admin'){
                    return view('admin_view');
                }else {
                    $file_upload = new UserFilesModel();
                    $all_files = $file_upload->where('user_id', $session->get('user_id'))->findAll();
                    // print_r($all_files);exit;
                    return view('user_view', ['data'=> $data, 'files'=> $all_files, 'success_msg'=> null, 'error_msg'=> null]);
                }
            }else{
                $session->destroy();
                return view('login');
            }
        }else{
            $session->destroy();
            return view('login');
        }
    }

    //============ verify phone API ==============
    public function verify_phone(){
        try {
            $session = session();
            if($session->get('logged_in') == false){
                $model = new UserModel();
                $phone = $this->request->getVar('phone');
                $data = $model->where('phone', $phone)->first();
                $array = [];
                if(isset($data)){
                    if(is_null($data['deleted_at'])){
                        $digits = 4;
                        $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);
                        $update_data = [
                            'otp' => $otp,
                            'expiry' => date('Y-m-d H:i:s')
                        ];
                        // $send_sms = $this->sendSMS($phone, $otp);
                        // if($send_sms == true){
                            if($model->update($data['id'], $update_data)){
                                $ses_data = [
                                    'user_id'       => $data['id'],
                                    'phone'     => $phone,
                                    'otp_sent'     => TRUE
                                ];
                                $session->set($ses_data);
                                $array['status'] = true;
                                $array['message'] = 'Send Otp successfully';
                                // return redirect()->to('/user/verify_otp');
                            }else{
                                $array['status'] = false;
                                $array['message'] = 'This user is not authorised.';
                            }
                        // }else{
                        //     $array['status'] = false;
                        //     $array['message'] = 'Something went wrong in send SMS.';
                        // }
                        
                    }else{
                        $array['status'] = false;
                        $array['message'] = 'This user is not authorised.';
                    }
                }else{
                    $array['status'] = false;
                    $array['message'] = 'This user not found.';
                }

                echo json_encode($array);
                return;
            }else {
                return redirect()->to('/user/index');
            }
        } catch (\Exception $e) {
            $array['status'] = false;
            $array['message'] = $e->getMessage();
            echo json_encode($array);
            return;            
        }
    }

    //============ laod verify otp view page ==============
    public function verify_otp(){
        $session = session();
        if($session->get('logged_in') == TRUE){
            return redirect()->to('/user/index');
        }else{
            if($session->get('otp_sent') == TRUE){
                $model = new UserModel();
                $phone = $this->request->getVar('phone');
                $user = $model->where('id', $session->get('user_id'))->first();
                $data['phone'] = $user['phone'];
                $data['otp'] = $user['otp'];
                $data['id'] = $user['id'];

                return view('verify_otp', $data);
            }else{
                return redirect()->to('/user/index');
            }
        }
    }

    //==================== Verify otp method ====================
    public function check_otp(){
        try {
            $session = session();
            if($session->get('otp_sent') == TRUE){
                $model = new UserModel();
                $otp = $this->request->getVar('otp');
                $phone = $this->request->getVar('phone');
                $data = $model->where('phone', $phone)->first();
                if(isset($data)){
                    if($otp == $data['otp']){
                        $add_minute = strtotime('+5 minutes', strtotime($data['expiry']));
                        $current_date = strtotime(date('Y-m-d H:i'));
                        if($current_date > $add_minute){
                            $array['status'] = false;
                            $array['message'] = 'Otp is Expire.';
                            echo json_encode($array);
                            return;
                        }else{
                            $ses_data = [
                                'user_id' => $data['id'],
                                'role' => $data['role'],
                                'logged_in' => TRUE,
                                'otp_sent'=> FALSE,
                            ];
                            $session->set($ses_data);
                            $array['status'] = true;
                            $array['message'] = 'Otp is valid.';
                            echo json_encode($array);
                            return;
                        }
                    }else{
                        $array['status'] = false;
                        $array['message'] = 'Invalid otp.';
                        echo json_encode($array);
                        return;
                    }
                }else{
                    $array['status'] = false;
                    $array['message'] = 'Data is not found.';
                    echo json_encode($array);
                    return;
                }
            }else{
                $array['status'] = false;
                $array['message'] = 'Send otp once again.';
                echo json_encode($array);
                return;
            }
        } catch (\Exception $e) {
            $array['status'] = false;
            $array['message'] = $e->getMessage();
            echo json_encode($array);
            return;
        }
        
    }

    //=================== Add user ====================
    public function add_user(){
        try {
            if(session()->get('logged_in') == TRUE || session()->get('logged_in') == 1){
                $model = new UserModel();
                $data = $model->where('id', session()->get('user_id'))->first();
                if(isset($data)){
                    if($data['role'] == 'admin'){
                        $email = $this->request->getVar('email');
                        $phone = $this->request->getVar('phone');
                        $name = $this->request->getVar('name');
                        $check_phone = $model->where('phone', $phone)->first();
                        $check_email = $model->where('email', $email)->first();
                        if(isset($check_phone)){
                            $array['status'] = false;
                            $array['message'] = 'This phone number is already exists.';
                            echo json_encode($array);
                            return;
                        }
                        if(isset($check_email)){
                            $array['status'] = false;
                            $array['message'] = 'This email is already exists.';
                            echo json_encode($array);
                            return;
                        }else{
                            $insert_data = [
                                'name' => $name,
                                'phone' => $phone,
                                'email' => $email,
                                'role' => 'user',
                                'otp' => NULL,
                                'expiry' => NULL
                            ];
    
                            if($model->save($insert_data)){
                                $array['status'] = true;
                                $array['message'] = 'User added successfully.';
                                echo json_encode($array);
                                return;
                            }else{
                                $array['status'] = false;
                                $array['message'] = 'Something went wrong to add user.';
                                echo json_encode($array);
                                return;
                            }
                        }
                    }else {
                        $array['status'] = false;
                        $array['message'] = 'Thuis user is not authorised to add user.';
                        echo json_encode($array);
                        return;
                    }
                }else{
                    $array['status'] = false;
                    $array['message'] = 'User is not found.';
                    echo json_encode($array);
                    return;
                }
            }else{
                $array['status'] = false;
                $array['message'] = 'User is not authenticate.';
                echo json_encode($array);
                return;
            }
        } catch (\Exception $e) {
            $array['status'] = false;
            $array['message'] = $e->getMessage();
            echo json_encode($array);
            return;
        }
    }

    //=================== logout ==================
    public function logout(){
        $session = session();
        $session->destroy();
        return redirect()->to('/user');
    }

    public function file_upload(Type $var = null){
        try {
            if(session()->get('logged_in') == TRUE || session()->get('logged_in') == 1){
                // print_r(session()->get());exit;
                $model = new UserModel();
                $data = $model->where('id', session()->get('user_id'))->first();
                $file_model = new UserFilesModel();
                $all_files = $file_model->where('user_id', session()->get('user_id'))->findAll();
                
                if(isset($data)){
                    $input = $this->request->getFile('file');
                    if(!$input){
                        return view('user_view', ['data'=> $data, 'files'=> $all_files, 'success_msg'=> null, 'error_msg'=> 'Choose a valid file.']);
                    }else{
                        $img = $this->request->getFile('file');
                        $img->move('uploads/user_files');
                
                        $data = [
                            'user_id' =>  session()->get('user_id'),
                            'name' =>  $img->getName(),
                            'type'  => $img->getClientMimeType()
                        ];
                
                        $save = $file_model->insert($data);
                        $all_files = $file_model->where('user_id', session()->get('user_id'))->findAll();
                        
                        return view('user_view', ['data'=> $data, 'files'=> $all_files, 'success_msg'=> 'File has successfully uploaded', 'error_msg'=> null]);
                    }
                }else{
                    return view('user_view', ['data'=> $data, 'files'=> $all_files, 'success_msg'=> null, 'error_msg'=> 'User not found.']);
                }
            }else{
                $data = $model->where('id', session()->get('user_id'))->first();
                return view('user_view', ['data'=> $data, 'files'=> $all_files, 'success_msg'=> null, 'error_msg'=> 'User is not authenticate.']);
            }
        } catch (\Exception $e) {
                $model = new UserModel();
                $data = $model->where('id', session()->get('user_id'))->first();
                $file_model = new UserFilesModel();
                $all_files = $file_model->where('user_id', session()->get('user_id'))->findAll();
            return view('user_view', ['data'=> $data, 'files'=> $all_files, 'success_msg'=> null, 'error_msg'=> $e->getMessage()]);            
        }
    }
    //==================== Send SMS Mehtod ====================
    protected function sendSMS($phone, $otp) {
        //======================= Fast2 SMS =============================
        $fields = array(
            "route" => "q",
            "message" => "Your OTP for login is: ".$otp,
            "language" => "english",
            "flash" => 0,
            "numbers" => $phone,
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($fields),
        CURLOPT_HTTPHEADER => array(
            "authorization: 98Qf1hIUV3ZbPpMtuDGcxkogWRaBseL7yiKNYrJzSEXjA4vw5lzboyT0lv1kwhfO5AuJZxgtN78dL9iS",
            "accept: */*",
            "cache-control: no-cache",
            "content-type: application/json"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
            echo "cURL Error #:" . $err;
        } else {
            return true;
            echo $response;
        }
    }

}
