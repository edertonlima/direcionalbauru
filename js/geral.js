//mascaras para tratar formatação dos campos

	function formatar(src, mask){
	var i = src.value.length;
	var saida = mask.substring(1,2);
	var texto = mask.substring(i)
	if (texto.substring(0,1) != saida)
	{
	src.value += texto.substring(0,1);
	}
	}

//mascaras nos campos.

	function mascara(o,f){
		v_obj=o
		v_fun=f
		setTimeout("execmascara()",1)
	}
	
	function execmascara(){
		v_obj.value=v_fun(v_obj.value)
	}
	
	function somenteNumeros(v){
		return v.replace(/\D/g,"")              //Somente números. Remove tudo o que não é número.
	}
	
	function telefone(v){
		v=v.replace(/\D/g,"")                  //Remove tudo o que não é dígito
		v=v.replace(/(\d{4})(\d)/,"$1-$2")     //Coloca hífen entre o quarto e o quinto dígitos
		return v
	}
	
	
//Palavra-chave dos buscadores

	function mostra() 
{
	if(document.getElementById("palavra").style.display == "none")
	{
		document.getElementById("palavra").style.display = "block";
	}
}
function esconde()
{
	if(document.getElementById("palavra").style.display == "block")
	{
		document.getElementById("palavra").style.display = "none";
	}
	else
	{
		document.getElementById("palavra").style.display = "none";
	}
}


//Formata todas as letras para MAIUSCULAS

	function maiuscula(z)
	{
		v = z.value.toUpperCase();
		z.value = v;
	}

//Formata todas as letras para minusculas

	function minusculas(z)
	{
		v = z.value.toLowerCase();
		z.value = v;
	}

//Formata a Primeira Letra de Cada Campo como Maiuscula

	function UcWords(campo)
	{
		val = campo.value;
		newVal = val
			.toLowerCase()
			.replace(/[^A-Za-záâàãäéêèëíîìïóõòôöúùûüç][A-Za-záâàãäéêèëíîìïóõòôöúùûüç]/g, function(m){return m.toUpperCase()})
			.replace(/[0-9][A-Za-záâàãäéêèëíîìïóõòôöúùûüç]/g, function(m){return m.toUpperCase()})
			.replace(/( (da|das|e|de|do|dos|para|na|nas|no|nos) )/gi, function(m){return m.toLowerCase()})
			.replace(/^./, function(m){return m.toUpperCase()})
		if (val != newVal)
		{
			campo.value = newVal;
		}
	}

//Passar para o próximo campo automaticamente
	var isNN = (navigator.appName.indexOf("Netscape")!=-1);
	function autoTab(input,len, e) {
	var keyCode = (isNN) ? e.which : e.keyCode;
	var filter = (isNN) ? [0,8,9] : [0,8,9,16,17,18,37,38,39,40,46];
	if(input.value.length >= len && !containsElement(filter,keyCode)) {
	input.value = input.value.slice(0, len);
	input.form[(getIndex(input)+1) % input.form.length].focus();
	}
	function containsElement(arr, ele) {
	var found = false, index = 0;
	while(!found && index < arr.length)
	if(arr[index] == ele)
	found = true;
	else
	index++;
	return found;
	}
	function getIndex(input) {
	var index = -1, i = 0, found = false;
	while (i < input.form.length && index == -1)
	if (input.form[i] == input)index = i;
	else i++;
	return index;
	}
	return true;
	}


//Repare que foram usados duas ID's: cpf e cpfl
//cpf se refere ao campo INPUT e cpfl se refere a tag LABEL
//o mesmo se aplica as ID's cnpj e cnpjl
 
	function checkdocs(){
	
	if(document.getElementById('cpf-radio').checked) {
	//Se o CPF for escolhido, habilita o mesmo:
	var cpf = document.getElementById('cpf'); 
	if (cpf){ cpf.style.display = "block";
	cpf.disabled = "";
	}
		
	//Então Desabilita CNPJ
	var cnpj = document.getElementById('cnpj'); 
	if (cnpj){
	cnpj.style.display = "none";
	cnpj.disabled = "disabled";
	cnpj.value = "";
	}
	}
	
	else if(document.getElementById('cnpj-radio').checked) {
	//Se CNPJ for escolhido, habilita o mesmo:
	var cnpj = document.getElementById('cnpj'); 
	if (cnpj){ cnpj.style.display = "block";
	cnpj.disabled = "";	
	
	
	//Então desabilita CPF
	var cpf = document.getElementById('cpf'); 
	if (cpf){ 
	cpf.style.display = "none";
	cpf.disabled = "disabled";
	cpf.value = "";
	}
	
	}
	
	}
	
	}

	
		// Active Menu Topo

var url = window.location;
$(function() {
$('header nav ul li a[href="' + url + '"]').addClass('active-menu-topo');

// Active Menu Aside

$('aside li a[href="' + url + '"]').addClass('active-menu-aside');
});