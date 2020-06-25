 
  <div class="modal fade" id="modal_actualizar_sostenedor">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
     
        <div class="modal-header">
          <h4 class="modal-title">Editar Sostenedor</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
      
        <div class="modal-body">
         <form id="formulario_update_sostenedor">
         <div class="row">
          <div class="col-md-4">
              <label>Nombre Sostenedor:</label>
              <input type="text" name="nom_soste_update" id="nom_soste_update" class="form-control" required/>
              <input type="text" name="id_soste_update" id="id_soste_update" class="invisible"/>
          </div>
          <div class="col-md-4">
             <label>Apellido Sostenedor:</label>
             <input type="text" name="apelli_soste_update" id="apelli_soste_update" class="form-control" required/>
          </div>
          <div class="col-md-4">
            <label>Run Sostenedor:</label>
            <input type="text" name="run_soste_update" id="run_soste_update" class="form-control" required/>
          </div>
          <div class="col-md-4">
             <label>Usuario Sostenedor:</label>
             <input type="text" name="user_soste_update" id="user_soste_update" class="form-control" readonly/>
             <input type="text" name="id_user_soste_update" id="id_user_soste_update" class="invisible"/>
          </div>
          <div class="col-md-4">
            <label>Contraseña Sostenedor:</label>
            <input type="text" name="pass_soste_update" id="pass_soste_update" class="form-control" readonly/>
          </div>
         </div>
        </div>              
        <div class="modal-footer">
        <input type="submit" id="actualiza_soste" class="btn btn-primary" value="Actualizar" />
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
        </form>
      </div>
    </div>
  </div>