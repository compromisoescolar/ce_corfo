
# ejecutar estas sentencias luego de migrar base de datos, para evitar errores

set global sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';

set session sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';

# en caso de otro error 

SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));

# para editar menu 

update ce_roles set menu="<li class='active treeview'>
    <a class='menu-flag' href='#'>
        <i class='fa fa-user-circle-o'></i> 
        <span> Profesor</span>
        <span class='pull-right-container'>
            <i class='fa fa-angle-left pull-right'></i>
        </span>
    </a>
    <ul class='treeview-menu'>
        <li class='active' style='padding: 5px;'>
            <a href='#'>
                <i class='fa fa-users' aria-hidden='true'></i>
                Curso
            </a>
        </li>
        <li style='padding: 5px;'>
            <a id='select_estudiante' href='#'>
                <i class='fa fa-user' aria-hidden='true'></i>
                Estudiantes
            </a>
        </li>
    </ul>
</li>" where id_rol = '1';



