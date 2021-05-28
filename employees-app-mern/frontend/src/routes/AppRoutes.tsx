import { BrowserRouter as Router, Switch, Route } from 'react-router-dom';

import { HomePage } from '../pages/not-found/HomePage';
import { Account } from '../components/account/Account';
import { Admin } from '../components/admin/Admin';
import { Login } from '../components/login/Login';
import { Project } from '../components/project/Project';
import { Projects } from '../components/projects/Projects';
import { Register } from '../components/register/Register';
import { NotFound } from '../pages/not-found/NotFound';

// MERN Stack 3 - Creando el Router - Mongo DB Express React JS Node, Luis Cabrera
export const AppRoutes = () => {
    return (
        <>
            <Router>
                <Switch>
                    <Route exact path="/" component={ HomePage }/>
                    <Route exact path="/login" component={ Login }/>
                    <Route exact path="/register" component={ Register }/>
                    <Route exact path="/account" component={ Account }/>
                    <Route exact path="/projects" component={ Projects }/>
                    <Route exact path="/project/:projectId" component={ Project }/>
                    <Route exact path="/admin/users" component={ Admin }/>

                    <Route path="*" component={ NotFound }/>
                </Switch>
            </Router>
        </>
    )
}
