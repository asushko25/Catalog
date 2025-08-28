import { useParams, Link } from 'react-router-dom';
import { useQuery } from '@tanstack/react-query';
import { getFinalPrice } from '../api/products';
import '../styles/pages/productPrice.scss';

export default function ProductPrice() {
  const { id } = useParams();

  const { data, isLoading, error } = useQuery({
    queryKey: ['price', id],
    queryFn: () => getFinalPrice(String(id)),
    enabled: !!id,
  });

  if (isLoading) return <div className="pp-wrap pp-loading">Loading…</div>;
  if (error) return <div className="pp-wrap pp-error">Failed to load.</div>;

  return (
    <div className="pp-wrap">
      <h2 className="pp-title">Final price</h2>
      <p className="pp-value">{data?.finalPrice}</p>
      <Link className="pp-link" to="/">Back</Link>
    </div>
  );
}
