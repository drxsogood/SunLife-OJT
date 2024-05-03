const inputs = document.querySelectorAll(".input");
const loginButton = document.getElementById("loginBtn");

function addcl() {
   let parent = this.parentNode.parentNode;
   parent.classList.add("focus");
}

function remcl() {
   let parent = this.parentNode.parentNode;
   if (this.value == "") {
      parent.classList.remove("focus");
   }
}

function redirectToAnotherPage() {
   window.location.href = 'back.html';
}

function validateForm() {
   try {
      const usernameValue = document.querySelector(".input[type='text']").value.trim();
      const emailValue = document.querySelector(".input[type='email']").value.trim();
      const passwordValue = document.querySelector(".input[type='password']").value.trim();

      if (usernameValue === "") {
         throw new Error("Username is required.");
      }

      if (emailValue === "") {
         throw new Error("Email is required.");
      }

      const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
      if (!emailRegex.test(emailValue)) {
         throw new Error("Invalid email format.");
      }

      if (passwordValue === "") {
         throw new Error("Password is required.");
      }

      return true;
   } catch (error) {
      alert(error.message);
      console.log(error.message);
      return false;
   } finally {
    
    function redirectToAnotherPage() {
        window.location.href = 'back.html';
     }
     
   }
}

inputs.forEach(input => {
   input.addEventListener("focus", addcl);
   input.addEventListener("blur", remcl);
});

loginButton.addEventListener("click", function (e) {
   if (!validateForm()) {
      e.preventDefault();
   } else {
      redirectToAnotherPage();
   }
});
