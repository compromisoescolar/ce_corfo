<div class="modal fade" id="modal_nuevo_usuario" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-left">Nuevo Usuario</h5>
                <button id="cerrar_crear" type="button" class="close" data-dismiss="modal">&times;</button>         
            </div>
            <div class="modal-body">
            <form enctype="multipart/form-data" id="formu_nuevo_usuario" method="post">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>RBD Usuario:</label>
                            <input name="rbd_usuario" id="rbd_usuario" type="text" class="form-control" placeholder="rbd usuario...">
                        </div>
                        <div class="col-md-4">
                            <label>Contraseña:</label>
                            <input name="contrase_usuario" id="contrase_usuario" type="password" class="form-control" placeholder="Contraseña" readonly>
                        </div>
                        <div class="col-md-4">                           
                            <label>Estado:</label>                         
                            <select name="select_estado" id="select_estado" class="form-control">
                            <option value="1">Habilitado</option>
                            <option value="0">Inactivo</option>
                            </select>
                        </div>  
                        <div class="col-md-2">
                            <input name="usuario_nuevo_estable" id="usuario_nuevo_estable" type="text" class="form-control invisible" value="Nuevo Usuario" readonly>
                        </div>                
                    </div>
            </div>
            <div class="modal-footer">
                <div class="col-md-6">
                    <button id="guardar_nuevo_usuario" type="submit" class="btn btn-primary float-right">Guardar</button>
                </div>

                <div class="col-md-6">
                    <button id="cerrar_modal_nuevo" type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                </div>
                </form>

            </div>
        </div>
    </div>
</div>
