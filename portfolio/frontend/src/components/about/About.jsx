import React from 'react'
import foto from '../img/foto.jpg';

export const About = () => {
    return (
        <>
            <section className="about" id="about">
                <h2 className="title">
                    Sobre mi
                </h2>
                <div className="max-width">
                    <div className="about-content">
                        <div className="column left">
                            <img src={ foto } alt="me" />
                        </div>
                        <div className="column right">
                            <div className="text">
                                Soy Bryan y soy un <span>
                                    Ingeniero en Sistemas
                                </span>
                            </div>
                            <p>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae recusandae provident facilis sapiente fugit ex obcaecati, doloribus, in id aliquid placeat dicta quasi perspiciatis tempore cupiditate quibusdam quas. Atque consequatur deserunt reiciendis numquam deleniti! Officiis amet sequi itaque doloribus praesentium odio ducimus, explicabo numquam quae eum molestiae exercitationem incidunt similique at delectus quas, nisi placeat velit fuga sapiente. Consequatur, nulla?
                            </p>
                            <a href="/">Descargar CV</a>
                        </div>
                    </div>
                </div>
            </section>
        </>
    )
}
