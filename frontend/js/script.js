let toggleBtn = document.getElementById('toggle-btn');
let body = document.body;
let darkMode = localStorage.getItem('dark-mode');

const enableDarkMode = () =>{
   toggleBtn.classList.replace('fa-sun', 'fa-moon');
   body.classList.add('dark');
   localStorage.setItem('dark-mode', 'enabled');
}

const disableDarkMode = () =>{
   toggleBtn.classList.replace('fa-moon', 'fa-sun');
   body.classList.remove('dark');
   localStorage.setItem('dark-mode', 'disabled');
}

if(darkMode === 'enabled'){
   enableDarkMode();
}

toggleBtn.onclick = (e) =>{
   darkMode = localStorage.getItem('dark-mode');
   if(darkMode === 'disabled'){
      enableDarkMode();
   }else{
      disableDarkMode();
   }
}

let profile = document.querySelector('.header .flex .profile');

document.querySelector('#user-btn').onclick = () =>{
   profile.classList.toggle('active');
   search.classList.remove('active');
}

let search = document.querySelector('.header .flex .search-form');

document.querySelector('#search-btn').onclick = () =>{
   search.classList.toggle('active');
   profile.classList.remove('active');
}

let sideBar = document.querySelector('.side-bar');

document.querySelector('#menu-btn').onclick = () =>{
   sideBar.classList.toggle('active');
   body.classList.toggle('active');
}

document.querySelector('#close-btn').onclick = () =>{
   sideBar.classList.remove('active');
   body.classList.remove('active');
}

window.onscroll = () =>{
   profile.classList.remove('active');
   search.classList.remove('active');

   if(window.innerWidth < 1200){
      sideBar.classList.remove('active');
      body.classList.remove('active');
   }
}

function registerStudent() {
   event.preventDefault();

   const name = document.getElementById("name").value;
   const email = document.getElementById("email").value;
   const password = document.getElementById("password").value;

   localStorage.removeItem('name');
   localStorage.removeItem('email');
   localStorage.removeItem('password');

   fetch('http://localhost:8000/signup.php', {
      method: 'POST',
      headers: {
         'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&user_type=${encodeURIComponent("student")}`
      })
      .then(response => response.json())
      .then(data => {
         alert('Signup successful: ' + JSON.stringify(data));
      })
      .catch(error => {
         console.error('Error:', error);
         alert('Signup failed: ' + error.message);
         return;
      });

   localStorage.setItem('name', name);
   localStorage.setItem('email', email);
   localStorage.setItem('password', password);

   window.location.href = "http://127.0.0.1:5500/frontend/home.html";
}

function loginStudent() {
   event.preventDefault();

   const email = document.getElementById("login-email").value;
   const password = document.getElementById("login-password").value;

   localStorage.removeItem('name');
   localStorage.removeItem('email');
   localStorage.removeItem('password');

   fetch('http://localhost:8000/login.php', {
      method: 'POST',
      headers: {
         'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&user_type=${encodeURIComponent("student")}`
      })
      .then(response => response.json())
      .then(data => {
         if (data.error) {
            console.error('Error:', data.error);
            alert('Signup failed: ' + data.error);
            return;
         }
         alert('Login successful: ' + JSON.stringify(data));
         localStorage.setItem('email', email);
         localStorage.setItem('password', password);
         window.location.href = "http://127.0.0.1:5500/frontend/home.html";
      })
}
