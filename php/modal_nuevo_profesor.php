
 
  <div class="modal fade" id="modal_profesor">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
     
        <div class="modal-header">
          <h4 class="modal-title">Nuevo Docente</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
      
        <div class="modal-body">
         <form id="formulario_profesor">
         <div class="row">
          <div class="col-md-4">
              <label>Nombre Docente:</label>
              <input type="text" name="nom_prof" id="nom_prof" class="form-control" required/>
          </div>
          <div class="col-md-4">
             <label>Apellido Docente:</label>
             <input type="text" name="apelli_prof" id="apelli_prof" class="form-control" required/>
          </div>
          <div class="col-md-4">
            <label>Run Docente:</label>
            <input type="text" name="run_prof" id="run_prof" class="form-control" required/>
          </div>
          <div class="col-md-4">
            <label>Email Docente:</label>
            <input type="mail" name="mail_prof" id="mail_prof" class="form-control"/>
          </div>
          <div class="col-md-4">
            <label>Usuario Docente:</label>
            <input type="text" name="user_docente" id="user_docente" class="form-control" readonly/>
          </div>
          <div class="col-md-4">
            <label>Contraseña Docente:</label>
            <input type="text" name="pass_docente" id="pass_docente" class="form-control" readonly/>
          </div>
         </div>
        </div>       
       
        <div class="modal-footer">
        <input type="submit" id="guarda_prof" class="btn btn-primary" value="Guardar" />
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
        </form>
      </div>
    </div>
  </div>