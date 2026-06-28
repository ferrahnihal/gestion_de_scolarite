function setRole(el,role){

document.querySelectorAll(".role-btn").forEach(b=>b.classList.remove("active"));

el.classList.add("active");

document.getElementById("role").value=role;

}