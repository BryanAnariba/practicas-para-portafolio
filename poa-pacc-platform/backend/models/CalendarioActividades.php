<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once('../../validators/validators.php');
    
    class Calendario { 
        protected $idActividad;
        protected $actividadAdmin;
        protected $idDimension;
        protected $Mes;
        protected $Anio;
        protected $Depa;

        protected function _construct($idActividad = null,$Mes = null,$idDimension = null,$Depa = null,$Anio = null, $actividadAdmin = null){
            $this->idActividad = $idActividad;
            $this->Mes = $Mes;
            $this->Depa = $Depa;
            $this->idDimension = $idDimension;
            $this->Anio = $Anio;
            $this->$actividadAdmin = $actividadAdmin;
        }

        public function getActividadAdmin(){
            return $this->actividadAdmin;
        }

        public function setActividadAdmin($actividadAdmin){
            $this->actividadAdmin = $actividadAdmin;
            return $this;
        }

        public function getAnio(){
            return $this->Anio;
        }

        public function setAnio($Anio){
            $this->Anio = $Anio;
            return $this;
        }

        public function getIdDimension(){
            return $this->idDimension;
        }

        public function setIdDimension($idDimension){
            $this->idDimension = $idDimension;
            return $this;
        }

        public function getIdActividad(){
            return $this->idActividad;
        }

        public function setIdActividad($idActividad){
            $this->idActividad = $idActividad;
            return $this;
        }
        
        public function getMes(){
            return $this->Mes;
        }

        public function setMes($Mes){
            $this->Mes = $Mes;
            return $this;
        }

        public function getDepa(){
            return $this->Depa;
        }

        public function setDepa($Depa){
            $this->Depa = $Depa;
            return $this;
        }

        public function getDimensionEstrategicas () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('SELECT * FROM DimensionEstrategica WHERE idEstadoDimension=1');
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar las actividades')
                    );
                }
            } catch (PDOException $ex) {
                return array(
                    'status'=> INTERNAL_SERVER_ERROR,
                    'data' => array('message' => $ex->getMessage())
                );
            } finally {
                $this->conexionBD = null;
            }
        }

        public function getActividadDescripcionAdmin () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("select DescripcionAdministrativa.idDimensionAdministrativa,DescripcionAdministrativa.idDescripcionAdministrativa,DescripcionAdministrativa.nombreActividad,
                                                    DescripcionAdministrativa.Descripcion,DescripcionAdministrativa.Cantidad,DescripcionAdministrativa.Costo,DescripcionAdministrativa.CostoTotal,
                                                    TipoPresupuesto.tipoPresupuesto,ObjetoGasto.abrev as ObjetoGasto,ObjetoGasto.DescripcionCuenta,DimensionEstrategica.dimensionEstrategica,
                                                    DescripcionAdministrativa.mesRequerido,Actividad.responsableActividad,Actividad.justificacionActividad,Actividad.medioVerificacionActividad,
                                                    Departamento.nombreDepartamento
                                                    from DescripcionAdministrativa
                                                    inner join TipoPresupuesto
                                                    on TipoPresupuesto.idTipoPresupuesto=DescripcionAdministrativa.idTipoPresupuesto
                                                    inner join ObjetoGasto
                                                    on ObjetoGasto.idObjetoGasto=DescripcionAdministrativa.idObjetoGasto
                                                    inner join Actividad
                                                    on Actividad.idActividad=DescripcionAdministrativa.idActividad
                                                    inner join DimensionEstrategica
                                                    on DimensionEstrategica.idDimension=Actividad.idDimension
                                                    inner join Usuario
                                                    on Usuario.idPersonaUsuario=Actividad.idPersonaUsuario
                                                    inner join Departamento
                                                    on Departamento.idDepartamento=Usuario.idDepartamento
                                                    where DescripcionAdministrativa.idDescripcionAdministrativa=$this->actividadAdmin"
                                                );
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar las actividades')
                    );
                }
            } catch (PDOException $ex) {
                return array(
                    'status'=> INTERNAL_SERVER_ERROR,
                    'data' => array('message' => $ex->getMessage())
                );
            } finally {
                $this->conexionBD = null;
            }
        }

        public function getActividadDescripcionAdminTodas () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("select year(Actividad.fechaCreacionActividad) as anio,DescripcionAdministrativa.idDimensionAdministrativa,DescripcionAdministrativa.idDescripcionAdministrativa,
                                                        DescripcionAdministrativa.nombreActividad,DescripcionAdministrativa.Descripcion,DescripcionAdministrativa.Cantidad,DescripcionAdministrativa.Costo,DescripcionAdministrativa.CostoTotal,
                                                        TipoPresupuesto.tipoPresupuesto,ObjetoGasto.abrev as ObjetoGasto,ObjetoGasto.DescripcionCuenta,DimensionEstrategica.dimensionEstrategica,DescripcionAdministrativa.mesRequerido,
                                                        Departamento.idDepartamento,Actividad.responsableActividad,Actividad.justificacionActividad,Actividad.medioVerificacionActividad,
                                                        Departamento.nombreDepartamento
                                                from DescripcionAdministrativa
                                                inner join TipoPresupuesto
                                                on TipoPresupuesto.idTipoPresupuesto=DescripcionAdministrativa.idTipoPresupuesto
                                                inner join ObjetoGasto
                                                on ObjetoGasto.idObjetoGasto=DescripcionAdministrativa.idObjetoGasto
                                                inner join Actividad
                                                on Actividad.idActividad=DescripcionAdministrativa.idActividad
                                                inner join DimensionEstrategica
                                                on DimensionEstrategica.idDimension=Actividad.idDimension
                                                inner join Usuario
                                                on Usuario.idPersonaUsuario=Actividad.idPersonaUsuario
                                                inner join Departamento
                                                on Departamento.idDepartamento=Usuario.idDepartamento
                                                where Departamento.idDepartamento=(select idDepartamento from Usuario u where u.idPersonaUsuario = Actividad.idPersonaUsuario);"
                                                );
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar las actividades')
                    );
                }
            } catch (PDOException $ex) {
                return array(
                    'status'=> INTERNAL_SERVER_ERROR,
                    'data' => array('message' => $ex->getMessage())
                );
            } finally {
                $this->conexionBD = null;
            }
        }

        public function getAnioPresupuesto () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('SELECT year(fechaPresupuestoAnual) FROM ControlPresupuestoActividad
                                                order by year(fechaPresupuestoAnual) desc;'
                                                );
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar las actividades')
                    );
                }
            } catch (PDOException $ex) {
                return array(
                    'status'=> INTERNAL_SERVER_ERROR,
                    'data' => array('message' => $ex->getMessage())
                );
            } finally {
                $this->conexionBD = null;
            }
        }

        public function getActividades () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('select a.idActividad,a.actividad,a.correlativoActividad,de.nombreDepartamento
                                                            ,(SELECT count(*) FROM DescripcionAdministrativa da where da.idActividad=a.idActividad) as NumeroDeActividadesDefinidas
                                                            ,a.idDimension,ta.TipoActividad
                                                    FROM Actividad a
                                                    inner join ResultadoInstitucional ri on a.idResultadoInstitucional=ri.idResultadoInstitucional
                                                    inner join TipoActividad ta on ta.idTipoActividad=a.idTipoActividad
                                                    inner join AreaEstrategica ae on ae.idAreaEstrategica=a.idAreaEstrategica
                                                    inner join ObjetivoInstitucional oi on a.idObjetivoInstitucional=oi.idObjetivoInstitucional
                                                    inner join Usuario u on u.idPersonaUsuario=a.idPersonaUsuario
                                                    inner join Departamento de on de.idDepartamento=u.idDepartamento
                                                    where YEAR(a.fechaCreacionActividad)=:Anio and a.idDimension=:idDimension
                                                    and de.idDepartamento = (select idDepartamento from Usuario u where u.idPersonaUsuario = a.idPersonaUsuario);'
                                                );
                $stmt->bindValue(':idDimension', $this->idDimension);
                $stmt->bindValue(':Anio', $this->Anio);
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar las actividades')
                    );
                }
            } catch (PDOException $ex) {
                return array(
                    'status'=> INTERNAL_SERVER_ERROR,
                    'data' => array('message' => $ex->getMessage())
                );
            } finally {
                $this->conexionBD = null;
            }
        }

        public function getActividadPorId () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('select
                                                        Actividad.idActividad,Actividad.actividad,Actividad.correlativoActividad,Departamento.nombreDepartamento,AreaEstrategica.areaEstrategica
                                                        ,ObjetivoInstitucional.objetivoInstitucional,Dimensionestrategica.dimensionEstrategica
                                                        ,(SELECT count(*) FROM DescripcionAdministrativa da where da.idActividad=Actividad.idActividad) as NumeroDeActividadesDefinidas,tipoActividad.TipoActividad
                                                    FROM actividad
                                                    inner join ResultadoInstitucional on Actividad.idResultadoInstitucional=ResultadoInstitucional.idResultadoInstitucional
                                                    inner join TipoActividad on tipoActividad.idTipoActividad=Actividad.idTipoActividad
                                                    inner join Dimensionestrategica on Dimensionestrategica.idDimension=Actividad.idDimension
                                                    inner join AreaEstrategica on AreaEstrategica.idAreaEstrategica=Actividad.idAreaEstrategica
                                                    inner join ObjetivoInstitucional on Actividad.idObjetivoInstitucional=ObjetivoInstitucional.idObjetivoInstitucional
                                                    inner join Usuario on Usuario.idPersonaUsuario=Actividad.idPersonaUsuario
                                                    inner join Departamento on Departamento.idDepartamento=usuario.idDepartamento
                                                    where Actividad.idActividad=:idActividad;'
                                                );
                $stmt->bindValue(':idActividad', $this->idActividad);
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al obtener informacion de la actividad')
                    );
                }
            } catch (PDOException $ex) {
                return array(
                    'status'=> INTERNAL_SERVER_ERROR,
                    'data' => array('message' => $ex->getMessage())
                );
            } finally {
                $this->conexionBD = null;
            }
        }

        public function getInfoActPorId () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('select Actividad.actividad,ResultadoInstitucional.resultadoInstitucional,Actividad.resultadosUnidad,Actividad.indicadoresResultado,
                                                        Actividad.justificacionActividad,Actividad.medioVerificacionActividad,Actividad.poblacionObjetivoActividad
                                                        ,DimensionEstrategica.dimensionEstrategica,Actividad.responsableActividad,Actividad.CostoTotal,CostoActividadPorTrimestre.Trimestre1
                                                        ,CostoActividadPorTrimestre.Trimestre2,CostoActividadPorTrimestre.Trimestre3,CostoActividadPorTrimestre.Trimestre4
                                                FROM Actividad
                                                inner join ResultadoInstitucional on Actividad.idResultadoInstitucional=ResultadoInstitucional.idResultadoInstitucional
                                                inner join DimensionEstrategica on DimensionEstrategica.idDimension=Actividad.idDimension
                                                inner join AreaEstrategica on AreaEstrategica.idAreaEstrategica=Actividad.idAreaEstrategica
                                                inner join ObjetivoInstitucional on Actividad.idObjetivoInstitucional=ObjetivoInstitucional.idObjetivoInstitucional
                                                inner join Usuario on Usuario.idPersonaUsuario=Actividad.idPersonaUsuario
                                                inner join Departamento on Departamento.idDepartamento=Usuario.idDepartamento
                                                inner join CostoActividadPorTrimestre on CostoActividadPorTrimestre.idActividad=Actividad.idActividad
                                                where Actividad.idActividad=:idActividad;'
                                                );
                $stmt->bindValue(':idActividad', $this->idActividad);
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al obtener los resultados de la unidad de la actividad')
                    );
                }
            } catch (PDOException $ex) {
                return array(
                    'status'=> INTERNAL_SERVER_ERROR,
                    'data' => array('message' => $ex->getMessage())
                );
            } finally {
                $this->conexionBD = null;
            }
        }
        public function obtenerActividadesPlanif () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('SELECT * FROM DescripcionAdministrativa where idActividad=:idActividad');
                $stmt->bindValue(':idActividad', $this->idActividad);
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al obtener informacion de la actividad')
                    );
                }
            } catch (PDOException $ex) {
                return array(
                    'status'=> INTERNAL_SERVER_ERROR,
                    'data' => array('message' => $ex->getMessage())
                );
            } finally {
                $this->conexionBD = null;
            }
        }
        public function obtenerActividadesPlanifPorId () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('SELECT * FROM DescripcionAdministrativa 
                                                inner join TipoPresupuesto 
                                                on TipoPresupuesto.idTipoPresupuesto=DescripcionAdministrativa.idTipoPresupuesto 
                                                inner join ObjetoGasto 
                                                on ObjetoGasto.idObjetoGasto=DescripcionAdministrativa.idObjetoGasto 
                                                inner join Actividad
                                                on Actividad.idActividad=DescripcionAdministrativa.idActividad
                                                inner join DimensionEstrategica
                                                on DimensionEstrategica.idDimension=Actividad.idDimension
                                                where idDescripcionAdministrativa=:idActividad');
                $stmt->bindValue(':idActividad', $this->idActividad);
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al obtener informacion de la actividad')
                    );
                }
            } catch (PDOException $ex) {
                return array(
                    'status'=> INTERNAL_SERVER_ERROR,
                    'data' => array('message' => $ex->getMessage())
                );
            } finally {
                $this->conexionBD = null;
            }
        }

        public function getActividadesPorDepa () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('select Actividad.idActividad,Actividad.actividad,Actividad.correlativoActividad,Departamento.nombreDepartamento
                                                        ,(SELECT count(*) FROM DescripcionAdministrativa where DescripcionAdministrativa.idActividad=Actividad.idActividad) as NumeroDeActividadesDefinidas
                                                        ,DimensionEstrategica.dimensionEstrategica,TipoActividad.TipoActividad
                                                FROM Actividad
                                                inner join ResultadoInstitucional on Actividad.idResultadoInstitucional=ResultadoInstitucional.idResultadoInstitucional
                                                inner join TipoActividad on TipoActividad.idTipoActividad=Actividad.idTipoActividad
                                                inner join DimensionEstrategica on DimensionEstrategica.idDimension=Actividad.idDimension
                                                inner join AreaEstrategica on AreaEstrategica.idAreaEstrategica=Actividad.idAreaEstrategica
                                                inner join ObjetivoInstitucional on Actividad.idObjetivoInstitucional=ObjetivoInstitucional.idObjetivoInstitucional
                                                inner join Usuario on Usuario.idPersonaUsuario=Actividad.idPersonaUsuario 
                                                inner join Departamento on Departamento.idDepartamento=Usuario.idDepartamento
                                                where YEAR(Actividad.fechaCreacionActividad)=:Anio and Actividad.idDimension=:idDimension and Departamento.idDepartamento=:Depa
                                                and Departamento.idDepartamento = (select idDepartamento from Usuario u where u.idPersonaUsuario = Actividad.idPersonaUsuario);'
                                                );
                $stmt->bindValue(':Depa', $this->Depa);
                $stmt->bindValue(':Anio', $this->Anio);
                $stmt->bindValue(':idDimension', $this->idDimension);
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar las actividades')
                    );
                }
            } catch (PDOException $ex) {
                return array(
                    'status'=> INTERNAL_SERVER_ERROR,
                    'data' => array('message' => $ex->getMessage())
                );
            } finally {
                $this->conexionBD = null;
            }
        }
}