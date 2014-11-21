function verif_pass(f) {
    if(f.password.value != f.password_verif.value) {
        alert("Passwords are not equal !");
        f.password_verif.style.backgroundColor = "#fba";
        f.password.style.backgroundColor = "#fba";
        return false;
    } else {
        return true;
    }
}