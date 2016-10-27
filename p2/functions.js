function loadContent(page, array) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("scrollable").innerHTML = this.responseText;
        }
    };
    if(array) {
        if(page.indexOf("?") == -1) {
            page += "?";
        }
        var count=0;
        array.forEach(function(entry) {
            count++;
            page += "&" + count + "=" + $(entry).val();
        });
    }
    xhttp.open("GET", typeof(page) === 'undefined'? "matches.php" : page, true);
    xhttp.send();
}

function updateUserCount() {
    var previous = document.getElementById("usercount").innerHTML;
    previous = previous? parseInt(previous.split(">").pop()) : "";
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("usercount").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "usercount.php?previous=" + previous, true);
    xhttp.send();
}

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
        loadContent("register.php?",['#user','#pass','#mail','#card','#exp']);
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

function validateBet(game, match) {
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
        loadContent("bet.php?game=" + game + "&match=" + match, ['input[name=team]:checked','#amount']);
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

function passwordStrength(password) {
    var desc = new Array();
    desc[0] = "Password Strength";
    desc[1] = "Too Short";
    desc[2] = "Weak";
    desc[3] = "Medium";
    desc[4] = "Strong";
    desc[5] = "Very Strong";
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
    strength = document.getElementById("strength");
    strength.innerHTML = desc[score];
    strength.className = "strength" + score;
}
