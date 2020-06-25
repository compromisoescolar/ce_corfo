
<div class="modal fade" id="modalactualizarestudiante" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-left">Actualizar Datos Estudiantes</h5>
                <button id="cerrar_actualizar" type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body">
            <form id="actualizar_curso_estudi">
                <div class="row">  
              
                            <div class="col-md-3">
                                <label>Nombres:</label>
                                <input id="nombre_es" name="nombre_es" type="text" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label>Apellidos:</label>
                                <input id="apelli_es" name="apelli_es" type="text" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label>Run:</label>
                                <input id="run_es_proble" name="run_es_proble" type="text" class="form-control run_es_proble">                                
                            </div>
                           
                            <div class="col-md-3">
                                <label>Curso:</label>
                                <input id="curso_es" name="curso_es" type="text" class="form-control">                                
                            </div>
                            
                            <div class="col-md-3">
                                <label>Ciudad</label>
                                <input id="ciu_est" name="ciu_est" type="text" class="form-control">                                
                            </div>
                            <div class="col-md-3">
                                <label>Token:</label>
                                <input id="token_es" name="token_es" type="text" class="form-control" placeholder="A19462725">                                
                            </div>
                            <div class="col-md-3">
                                <label>Fecha Nacimiento:</label>
                                <input id="fecha_naci_es" name="fecha_naci_es" class="datepicker form-control">
                                <input id="idestudiante_update" name="idestudiante_update" class="form-control" hidden>  
                                <input id="idestablecimiento_update" name="idestablecimiento_update" class=" form-control" hidden>  
                                <input id="iddocente_update" name="iddocente_update" class=" form-control" hidden>  
                                <input id="idcurso_update" name="idcurso_update" class=" form-control" hidden>                         
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

