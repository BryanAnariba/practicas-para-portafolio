import { Route, Redirect } from "react-router-dom"

import { useAuth } from '../auth/useAuth';
export const PublicRoutes = (props) => {
    const { user } = useAuth();
    return (
        <>
            { 
                (user) 
                ? 
                    <Redirect to="/login" />
                : 
                    <Route { ...props } /> 
            }
        </>
    )
}