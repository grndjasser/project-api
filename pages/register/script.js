async function register(){
    url='http://localhost/website/shop/api/register.php';
    username=document.getElementById('username').value;
    email=document.getElementById('email').value;
    password=document.getElementById('password').value;
    phone=document.getElementById('phone').value;
    chkbox=document.getElementById('is_customer');
    if(chkbox.checked){
        is_customer='false';
    }
    else{
        is_customer="true";
    }
    data={
        "username":username,
        "email":email,
        "password":password,
        "phone":phone,
        "is_customer":is_customer
    };
    const response=await fetch(url,{
        method:'POST',
        headers:{"Content-type":"application/json"},
        body:JSON.stringify(data),
    });
    const data_2=await response.json();
    console.log(data_2);
    if(data_2.code==200){
        window.location.href='http://localhost/website/shop/pages/login/';
    }
}