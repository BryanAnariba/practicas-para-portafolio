import React from 'react';
import { Navbar, Nav, Container, NavDropdown } from 'react-bootstrap';
import { NavLink } from 'react-router-dom';

export const Header = () => {
    return (
        <>
            <Navbar bg="dark" variant="dark" expand="lg">
                <Container>
                    <Navbar.Brand as={ NavLink } to="/">Employees App</Navbar.Brand>
                    <Navbar.Toggle aria-controls="basic-navbar-nav" />
                    <Navbar.Collapse id="basic-navbar-nav" className="justify-content-end">
                        <Nav className="mr-auto ml-auto text-center">
                            <Nav.Link  as={ NavLink } to="/projects">Projects</Nav.Link>
                            <NavDropdown title="Admin" id="basic-nav-dropdown">
                                <NavDropdown.Item  as={ NavLink } to="/admin/users">Users</NavDropdown.Item>
                            </NavDropdown>
                        </Nav>
                        <Nav className="ml-auto">
                            <Nav.Link as={ NavLink } to="/login">Login</Nav.Link>
                            <Nav.Link as={ NavLink } to="/register">SignUp</Nav.Link>
                            <Nav.Link as={ NavLink } to="/account">Account</Nav.Link>
                        </Nav>
                    </Navbar.Collapse>
                </Container>
            </Navbar>
        </>
    )
}
