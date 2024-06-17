async function login(){
    url='http://localhost/website/shop/api/login.php';
    email=document.getElementById('email').value;
    password=document.getElementById('password').value;

    data={
        "email":email,
        "password":password,
    }
    const response=await fetch(url,{
        method:"POST",
        headers:{"Content-type":"application/json"},
        body:JSON.stringify(data),
    })
    const data_2=await response.json();
    console.log(data_2);
    if(data_2.status==true){
        window.location.href='http://localhost/website/shop/pages/profile/';
    }
}