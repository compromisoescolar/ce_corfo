 
  <div class="modal fade" id="modal_sostenedor">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">          
        <div class="modal-header">
          <h4 class="modal-title">Nuevo Sostenedor</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
      
        <div class="modal-body">
         <form id="formulario_sostenedor">
         <div class="row">
          <div class="col-md-4">
              <label>Nombre Sostenedor:</label>
              <input type="text" name="nom_soste" id="nom_soste" class="form-control" required/>
          </div>
          <div class="col-md-4">
             <label>Apellido Sostenedor:</label>
             <input type="text" name="apelli_soste" id="apelli_soste" class="form-control" required/>
          </div>
          <div class="col-md-4">
            <label>Run Sostenedor:</label>
            <input type="text" name="run_soste" id="run_soste" class="form-control" required/>
          </div>
          <div class="col-md-4">
             <label>Usuario Sostenedor:</label>
             <input type="text" name="user_sostenedor" id="user_sostenedor" class="form-control" readonly/>
          </div>
          <div class="col-md-4">
            <label>Contraseña Sostenedor:</label>
            <input type="text" name="pass_sostenedor" id="pass_sostenedor" class="form-control" readonly/>
          </div>
         </div>
        </div>       
       
        <div class="modal-footer">
        <input type="submit" id="guarda_soste" class="btn btn-primary" value="Guardar" />
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
        </form>
      </div>
    </div>
  </div>