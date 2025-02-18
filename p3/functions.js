function validateRegister() {
    var regex;
    var formok = true;
    var user = document.forms["regform"]["user"].value;
    regex = /^[a-zA-Z0-9]+$/;
    if(user == null || user == '' || !regex.test(user)) {
        document.getElementById("user_error").innerHTML = "Invalid user name.";
        formok = false;
    } else {
        document.getElementById("user_error").innerHTML = "";
    }
    var pass = document.forms["regform"]["pass"].value;
    var passrep = document.forms["regform"]["passrep"].value;
    if(pass == null || pass == '') {
        document.getElementById("pass_error").innerHTML = "Invalid password.";
        formok = false;
    } else if (passrep == null || passrep == '' || passrep != pass) {
        document.getElementById("pass_error").innerHTML = "Password fields do not match.";
        formok = false;
    } else {
        document.getElementById("pass_error").innerHTML = "";
    }
    var mail = document.forms["regform"]["mail"].value;
    regex = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
    if(mail == null || mail == '' || !regex.test(mail)) {
        document.getElementById("mail_error").innerHTML = "Invalid e-mail address.";
        formok = false;
    } else {
        document.getElementById("mail_error").innerHTML = "";
    }
    var card = document.forms["regform"]["card"].value;
    var exp = document.forms["regform"]["exp"].value;
    regex = /^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/;
    var regex2 = /^(0[1-9]|1[0-2])\/?([0-9]{4}|[0-9]{2})$/;
    if(card == null || card == '' || !regex.test(card)) {
        document.getElementById("card_error").innerHTML = "Invalid credit card number.";
        formok = false;
    } else if(exp == null || exp == '' || !regex2.test(exp)) {
        document.getElementById("card_error").innerHTML = "Invalid expiry date.";
        formok = false;
    } else {
        document.getElementById("card_error").innerHTML = "";
    }
    if(formok) {
        loadContent("register.php",['#user','#pass','#mail','#card','#exp']);
    }
}

function validateCredit(option) {
    var min = parseInt(document.getElementById("amount").getAttribute("min"));
    var max = parseInt(document.getElementById("amount").getAttribute("max"));
    var step = parseInt(document.getElementById("amount").getAttribute("step"));
    var amount = document.getElementById("amount").value;
    if(amount == null || amount == '' || isNaN(amount)) {
        document.getElementById("amount_error").innerHTML = "Please write a correct amount.";
    } else if(amount < min) {
        document.getElementById("amount_error").innerHTML = "The minimum amount you can " + option + " is " + min + " €.";
    } else if(amount > max) {
        document.getElementById("amount_error").innerHTML = "The maximum amount you can " + option + " is " + max + " €.";
    } else if(amount % step !== 0) {
        document.getElementById("amount_error").innerHTML = "No loose change below " + step + " €.";
    } else {
        document.getElementById("amount_error").innerHTML = "";
        loadContent("credit.php?option=" + option, ['#amount']);
    }
}

function validateBet(betid, edit) {
    var min = parseInt(document.getElementById("amount").getAttribute("min"));
    var max = parseInt(document.getElementById("amount").getAttribute("max"));
    var step = parseInt(document.getElementById("amount").getAttribute("step"));
    var amount = document.getElementById("amount").value;
    if(amount == null || amount == '' || isNaN(amount)) {
        document.getElementById("amount_error").innerHTML = "Please write a correct amount.";
    } else if(amount < min) {
        document.getElementById("amount_error").innerHTML = "The minimum bet is " + min + " €.";
    } else if(amount > max) {
        document.getElementById("amount_error").innerHTML = "The maximum bet is " + max + " €.";
    } else if(amount % step !== 0) {
        document.getElementById("amount_error").innerHTML = "No loose change below " + step + " €.";
    } else {
        document.getElementById("amount_error").innerHTML = "";
        loadContent("bet.php?betid=" + betid + "&edit=" + (edit || "false"), ['input[name=team]:checked','#amount']);
    }
}

function updateBet(wnnr) {
    var min = parseInt(document.getElementById("amount").getAttribute("min"));
    var max = parseInt(document.getElementById("amount").getAttribute("max"));
    var amount = parseInt(document.getElementById("amount").value) || document.getElementById("wnnrside").innerHTML.slice(0, -2);
    if(wnnr) {
        $("#wnnrside").remove();
        $("#wnnrarrow").remove();
        if(wnnr == "0") {
            $("#match").prepend("<div class='side0' id='wnnrside'></div><div class='arrow0' id='wnnrarrow'></div>");
        } else if(wnnr == "1") {
            $("#match").append("<div class='side1' id='wnnrside'></div><div class='arrow1' id='wnnrarrow'></div>");
        }
    }
    document.getElementById("wnnrside").innerHTML = Math.min(Math.max(amount, min), max) + " €";
}

function showBetDetails(id) {
    var details = $("#details" + id);
    if(details.height() === 0) {
        details.animate({height: "20"}, {queue: false});
    } else {
        details.animate({height: "0"}, {queue: false});
    }
}

function passwordStrength(password) {
    var desc = new Array(
        "Password Strength",
        "Too Short",
        "Weak",
        "Medium",
        "Strong",
        "Very Strong"
    );
    var bg = new Array(
        "#f5f5f5",
        "linear-gradient(to right, #ff8b94 0%, #ff8b94 20%, #f5f5f5 20%, #f5f5f5 100%)",
        "linear-gradient(to right, #ffaaa5 0%, #ffaaa5 40%, #f5f5f5 40%, #f5f5f5 100%)",
        "linear-gradient(to right, #ffd3b6 0%, #ffd3b6 60%, #f5f5f5 60%, #f5f5f5 100%)",
        "linear-gradient(to right, #dcedc1 0%, #dcedc1 80%, #f5f5f5 80%, #f5f5f5 100%)",
        "#a8e6cf"
    );
    var score = 0;
    if(password) {
        score++;
        if (password.length > 4) {
            score++;
            if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;
            if (password.match(/\d+/)) score++;
            if ( password.match(/.[\!,\@,\#,\$,\%,\^,\&,\*,\?,\~,\-,\(,\),\[,\]]/) ) score++;
        }
    }
    strength = $("#strength");
    strength.html(desc[score]);
    strength.css("background", bg[score]);
}
