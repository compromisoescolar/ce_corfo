
<!-- Large modal -->

<!-- Modal -->
<div class="modal fade" id="modal_nuevo" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-left">Nuevo Estudiantes Asociado al Curso</h5>
                <button id="cerrar_crear" type="button" class="close" data-dismiss="modal">&times;</button>         
            </div>
            <div class="modal-body">
                <form id="crear_estudiante">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Nombres:</label>
                            <input id="new_name" name="new_name" type="text" class="form-control letras" placeholder="Nombres">
                        </div>
                        <div class="col-md-3">
                            <label>Apellidos:</label>

                            <input id="new_apellidos" name="new_apellidos" type="text" class="form-control letras" placeholder="Apellidos">
                        </div>
                        <div class="col-md-3">                           
                            <label>Run:</label>                          
                            <!--<input id="new_run" type="text" class="form-control" placeholder="run">-->
                            <input id="new_run" name="new_run" type="text" class="form-control demo_run">                      
                            
                        </div>


                        <div class="col-md-3">
                            <label>Ciudad:</label>
                            <input id="ciudad_estudiante" name="ciudad_estudiante" type="text" class="form-control" placeholder="Ciudad o Región">

                        </div>
                        <div class="col-md-3">
                            <label>Fecha Nacimiento:</label>      
                            <input id="fecha_naci_es_new" name="fecha_naci_es_new"  class="datepicker form-control" placeholder="Fecha">    
                        </div>
                        
                    
                    </div>
               
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">cerrar</button>
             <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
               


            </div>
        </div>
    </div>
</div>

