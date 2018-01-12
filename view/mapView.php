<link rel="stylesheet" type="text/css" href="css/map.css">
<link rel="stylesheet" href="css/about_same.css">

<div class="layout_block">
	<h3 class="title">Контакты</h3>
	<div id="about_logo"><img src="images/main/logos/main_logo.svg"></div>
	<div class="contacts_table">
		<div class="c_tab_box">
			<table>
				 <tr>
				    <td class="cont_tab_titl">Адрес:</td>
				    <td>Украина,г.Одесса</td>
				 </tr>
				 <tr>
				    <td class="cont_tab_titl">Телефон:</td>
				    <td>+38 (095) 555-55-55</td>
				 </tr>
				  <tr>
				    <td></td>
				    <td>+38 (095) 555-55-55</td>
				 </tr>
				  <tr>
				    <td></td>
				    <td>+38 (095) 555-55-55</td>
				 </tr>
			</table>
			<table>
				 <tr>
				    <td class="cont_tab_titl">E-mail:</td>
				    <td>+38 (095) 555-55-55</td>
				  </tr>
				  <tr>
				    <td class="cont_tab_titl">Skype:</td>
				    <td>+38 (095) 555-55-55</td>
				  </tr>
				  <tr>
				    <td class="cont_tab_titl">VK:</td>
				    <td>+38 (095) 555-55-55</td>
				  </tr>
			</table>
		</div>
	</div>

	<h3 class="title">Мы на карте</h3>
	<div class="on_map">
		<iframe class="google_map" src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d21989.275694003612!2d30.749345749999996!3d46.45544415!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sru!2sua!4v1495122463319" frameborder="0" style="border:0" allowfullscreen></iframe>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {

		$('.on_map').addClass("hidden").viewportChecker({
            classToAdd:'visible animated pulse',
		});
	});
</script>