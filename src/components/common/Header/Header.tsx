import { Link } from "react-router-dom";
import "./header.scss";

export const Header = () => {
  return (
    <nav>
      <Link to="/">Products</Link>
      <Link to="/new">Add product</Link>
    </nav>
  );
};
