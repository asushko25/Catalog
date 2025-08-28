import {
  BrowserRouter,
  Routes,
  Route,
  Navigate,
  useParams,
} from "react-router-dom";
import ProductsList from "./pages/ProductsList";
import ProductCreate from "./pages/ProductCreate";
import ProductPrice from "./pages/ProductPrice";
import { Header } from "./components/common/Header/Header";

function LegacyPriceRoute() {
  const { id } = useParams();
  return <Navigate to={`/products/${id}/price`} replace />;
}

export default function App() {
  return (
    <BrowserRouter basename={import.meta.env.BASE_URL}>
      <Header />

      <Routes>
        <Route path="/" element={<ProductsList />} />
        <Route path="/new" element={<ProductCreate />} />
        <Route path="/products/:id/price" element={<ProductPrice />} />
        <Route path="/price/:id" element={<LegacyPriceRoute />} />
      </Routes>
    </BrowserRouter>
  );
}
