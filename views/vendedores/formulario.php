<fieldset>
    <legend>Información General</legend>

    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" placeholder="Nombre Vendedor(a)" value="<?php echo s($vendedor->nombre); ?>">

    <label for="apellido">Apellido:</label>
    <input type="text" id="apellido" name="apellido" placeholder="Apellido Vendedor(a)" value="<?php echo s($vendedor->apellido); ?>">

    <label for="imagen">Imagen:</label>
    <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

    <?php if ($vendedor->imagen): ?>
        <img src="/imagenes/<?php echo $vendedor->imagen ?>" class="imagen-small">
    <?php endif; ?>
</fieldset>

<fieldset>
    <legend>Información Extra</legend>
    <label for="telefono">Teléfono:</label>
    <input type="text" id="telefono" name="telefono" placeholder="Teléfono Vendedor(a)" value="<?php echo s($vendedor->telefono); ?>">

    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" placeholder="Email Vendedor(a)" value="<?php echo s($vendedor->email); ?>">
</fieldset>