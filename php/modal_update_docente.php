 
  <div class="modal fade" id="modal_actualizar_docente">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">          
        <div class="modal-header">
          <h4 class="modal-title">Editar Docente</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
      
        <div class="modal-body">
         <form id="formulario_update_docente">
         <div class="row">
          <div class="col-md-4">
              <label>Nombre Docente:</label>
              <input type="text" name="nom_docente_update" id="nom_docente_update" class="form-control" required/>
              <input type="text" name="id_docente_update" id="id_docente_update" class="invisible"/>
          </div>
          <div class="col-md-4">
             <label>Apellido Docente:</label>
             <input type="text" name="apelli_docente_update" id="apelli_docente_update" class="form-control" required/>
          </div>
          <div class="col-md-4">
            <label>Run Docente:</label>
            <input type="text" name="run_docente_update" id="run_docente_update" class="form-control" required/>
          </div>
          <div class="col-md-4">
            <label>Email Docente:</label>
            <input type="email" name="mail_docente_update" id="mail_docente_update" class="form-control" required/>
          </div>
          <div class="col-md-4">
            <label>Usuario Docente:</label>
           
            <input type="text" name="user_docente_update" id="user_docente_update" class="form-control" readonly/>
            <input type="text" name="id_docente_user_update" id="id_docente_user_update" class="invisible"/>
          </div>
          <div class="col-md-4">
            <label>Contraseña Docente:</label>
            <input type="text" name="pass_docente_update" id="pass_docente_update" class="form-control" readonly/>
          </div>
         </div>
        </div>       
       
        <div class="modal-footer">
        <input type="submit" id="actualiza_docente" class="btn btn-primary" value="Actualizar" />
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
        </form>
      </div>
    </div>
  </div>