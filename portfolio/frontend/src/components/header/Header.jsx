import React, { useState } from 'react';
import programador from '../img/programador.svg';


export const Header = () => {
    const burguerButton = 'fas fa-bars';
    const xButton = 'fas fa-times';
    const [iconSelected, setIconSelected] = useState(burguerButton);
    const changeIcon = () => {
        if (iconSelected === burguerButton) {
            setIconSelected(xButton);
        } else {
            setIconSelected(burguerButton);
        }
    }
    return (
        <nav className="nabvar animate__animated animate__lightSpeedInLeft" id="header">
            <div className="max-width">
                <div className="logo">
                    <a href="/" className="anchor-tag">
                        <img src={ programador } alt="logo" />
                        Porta<span>folio.</span>
                    </a>
                </div>
                <ul className="menu">
                    <li>
                        <a href="/">
                            Inicio
                        </a>
                    </li>
                    <li>
                        <a href="/">
                            Acerca de mi
                        </a>
                    </li>
                    <li>
                        <a href="/">
                            Servicios
                        </a>
                    </li>
                    <li>
                        <a href="/">
                            Habilidades
                        </a>
                    </li>
                    <li>
                        <a href="/">
                            Equipos
                        </a>
                    </li>
                    <li>
                        <a href="/">
                            Contacto
                        </a>
                    </li>
                </ul>
                <div className="menu-btn" onClick={ () => changeIcon() }>
                    <i id="icon" className={ iconSelected }></i>
                </div>
            </div>
        </nav>
    )
}
