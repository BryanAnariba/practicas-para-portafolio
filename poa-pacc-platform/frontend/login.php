    <body>
        <div class="container">
            <div class="forms-container">
                <div class="signin-signup">
                    <form class="sign-in-form">
                        <h2 class="title animate__animated animate__flipInX">Inicio de sesión</h2>
                        <div class="input-field animate__animated animate__flipInX">
                            <i class="fas fa-user"></i>
                            <input type="text" placeholder="Correo Electronico UNAH" id="correoInstitucional"/>
                        </div>
                        <span id="span-correoInstitucional" class="text-small text-danger d-none">
                        </span>
                        <div class="input-field animate__animated animate__flipInX mb-4">
                            <i class="fas fa-lock"></i>
                            <input type="password" placeholder="Contraseña" id="passwordEmpleado"/>
                        </div>
                        <span id="span-passwordEmpleado" class="text-small text-danger d-none">
                        </span>
                        <button 
                            type="button" 
                            class="btn solid btn-block animate__animated animate__flipInX" 
                            id="sign-up-btn" 
                            alt="Inicio de sesion"
                            onclick="iniciarSesion()">
                            Login
                        </button>
                        <span id="span-campos" class="text-center text-small text-danger d-none" style="text-align:center;">
                        </span>
                    </form>
                </div>
            </div>

            <div class="panels-container">
                <div class="panel left-panel">
                    <div class="content animate__animated animate__fadeInTopLeft">
                        <h3>Bienvenido al sistema POA PACC</h3>
                        <p>
                            Para acceder al sistema deberás contar con las debidas credenciales de acceso.
                        </p>
                        <button 
                            type="button" 
                            class="btn solid btn-block" 
                            id="sign-up-btn" 
                            alt="Olvidaste tu contraseña"
                            onclick="openModalRecuperaCuenta()"
                            >
                            Olvidaste tu contraseña
                        </button>
                    </div>
                    <img src="./frontend/public/img/login/login-avatar.svg" class="image animate__animated animate__fadeInTopLeft" alt="login-avatar" />
                </div>
            </div>
        </div>