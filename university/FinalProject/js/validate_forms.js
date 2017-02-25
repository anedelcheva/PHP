function emailValidator()
{
	var email = document.getElementById("email").value;
	var pattern = /^([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)@([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)[\\.]([a-zA-Z]{2,9})$/;
	if (!pattern.test(email)) {
		document.getElementById("email").style.color = "red";
	}
	else
		document.getElementById("email").style.color = "#5AB034";
}

function passwordOnFocus(obj)
{
	var toolTip = document.getElementById("tooltip");
	toolTip.innerHTML = "Дължината на паролата е поне 7 символа. Използвайте поне 1 цифра, 1 малка буква и 1 голяма буква."
	toolTip.style.display = "block";
	toolTip.style.top = "212px";
	toolTip.style.left = "-150px";
	toolTip.style.width = "135px";
	toolTip.style.textAlign = "center";
}

function passwordBlur() {
	var tooltip = document.getElementById("tooltip");
	tooltip.style.display = "none";
}