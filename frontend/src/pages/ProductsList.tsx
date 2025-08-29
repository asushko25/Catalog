import { useQuery } from '@tanstack/react-query';
import { useMemo, useState } from 'react';
import { Link } from 'react-router-dom';
import { listProducts } from '../api/products';
import type { Product } from '../types/product';
import '../styles/pages/productsList.scss';

export default function ProductsList() {
  const [category, setCategory] = useState('');
  const [sortDir, setSortDir] = useState<'asc' | 'desc'>('asc');

  const { data, isLoading, error } = useQuery({
    queryKey: ['products'],
    queryFn: listProducts,
    staleTime: 0,
    refetchOnMount: 'always',
    refetchOnWindowFocus: true,
    refetchOnReconnect: true,
  });

  const view = useMemo(() => {
    const list: Product[] = (data ?? []) as Product[];
    const filtered = category.trim()
      ? list.filter((p) =>
          p.category.toLowerCase().includes(category.toLowerCase())
        )
      : list;

    return filtered.sort((a, b) =>
      sortDir === 'asc' ? a.priceGross - b.priceGross : b.priceGross - a.priceGross
    );
  }, [data, category, sortDir]);

  if (isLoading) return <div className="pl-wrap pl-loading">Loading…</div>;
  if (error) return <div className="pl-wrap pl-error">Failed to load.</div>;

  return (
    <div className="pl-wrap">
      <h2 className="pl-title">Products</h2>

      <div className="pl-toolbar">
        <input
          className="pl-filter"
          placeholder="Filter by category…"
          value={category}
          onChange={(e) => setCategory(e.target.value)}
        />
        <select
          className="pl-select"
          value={sortDir}
          onChange={(e) => setSortDir(e.target.value as 'asc' | 'desc')}
        >
          <option value="asc">Price ↑</option>
          <option value="desc">Price ↓</option>
        </select>
        <Link className="pl-add" to="/new">
          + Add product
        </Link>
      </div>

      <ul className="pl-list">
        {view.map((p) => (
          <li className="pl-item" key={p.id}>
            <span className="pl-name">{p.name}</span>
            <span className="pl-meta">
              — {p.priceGross} {p.currency} [{p.category}]
            </span>
            <Link to={`/products/${p.id}/price`}>final price</Link>
          </li>
        ))}
      </ul>

      {view.length === 0 && <p className="pl-empty">Nothing found.</p>}
    </div>
  );
}
