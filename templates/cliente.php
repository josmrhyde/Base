{% extends "layout.html.twig" %}

{% block content %}
	<form action="{{ app.url_generator.generate('registrar_item') }}" method="post" name="cliente">
		<table style ="margin: 0 auto;", "text-align:center;">
			<tr>
				<td><p class="s" align="right">ID del Cliente: </p></td>
				<td><input type="text" size="36" name="id_cliente" id="id_cliente" required></td>
	        </tr>
			<tr>
				<td><p class="s" align="right">Clave: </p></td>
				<td><input type="text" size="36" name="clave" required></td>
			</tr>
			<tr>
				<td><p class="s" align="right">Nombre(s): </p></td>
				<td><input type="text" size="36" name="nombre" required></td>
			</tr>
	        <tr>
				<td><p class="s" align="right">Apellidos: </p></td>
				<td><input type="text" size="36" name="apellidos" required</td>
			</tr>
			<tr>
				<td><p class="s" align="right">Correo: </p></td>
				<td><input type="text" size="36" name="correo" required></td>
			</tr>
			<tr>
				<td><p class="s" align="right">Calle: </p></td>
				<td><input type="text" size="36" name="calle" required></td>
			</tr>			
			<tr>
				<td><p class="s" align="right">Colonia: </p></td>
				<td><input type="text" size="36" name="colonia" required></td>
			</tr>
			<tr>
				<td><p class="s" align="right">CP: </p></td>
				<td><input type="text" size="36" name="cp" required></td>
			</tr>
			<tr>
				<td><p class="s" align="right">Ciudad: </p></td>
				<td><input type="text" size="36" name="ciudad" required></td>
			</tr>
			<tr>
				<td><p class="s" align="right">País: </p></td>
				<td><input type="text" size="36" name="pais" required></td>
			</tr>

			<tr>
				<td><p class="s" align="right">Teléfono: </p></td>
				<td><input type="text" size="36" name="telefono" required></td>
			</tr>
			<tr>					
				<td COLSPAN="2"><center><input type="submit" id="boton" name="btn_registrar" value="Registrar"></center></td>
			</tr>
		</table>
	</form>
{% endblock %}