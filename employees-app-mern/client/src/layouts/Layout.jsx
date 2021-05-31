import { Header } from '../components/header/Header';


export const Layout = ({ children }) => {
    return (
        <>
            <Header />
            { children }
        </>
    )
}
