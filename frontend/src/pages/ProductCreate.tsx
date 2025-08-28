import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { createProduct } from '../api/products';
import '../styles/pages/productCreate.scss';

type FormErrors = Record<string, string[]>;

export default function ProductCreate() {
  const [externalId, setExternalId] = useState('');
  const [name, setName] = useState('');
  const [category, setCategory] = useState('');
  const [priceGross, setPriceGross] = useState('');
  const [currency, setCurrency] = useState('PLN');
  const [errors, setErrors] = useState<FormErrors>({});
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  const onSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setErrors({});
    setLoading(true);
    try {
      await createProduct({
        externalId: externalId || null,
        name,
        category,
        priceGross,
        currency,
      });
      navigate('/');
    } catch (e: any) {
      const map: FormErrors = {};
      (e?.apiErrors ?? [{ field: '_', message: 'Unknown error' }]).forEach(
        (er: { field?: string | null; message: string }) => {
          const key = er.field || '_';
          map[key] = map[key] || [];
          map[key].push(er.message);
        }
      );
      setErrors(map);
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={onSubmit}>
      <h2>Add product</h2>

      <input
        placeholder="externalId (optional)"
        value={externalId}
        onChange={(e) => setExternalId(e.target.value)}
      />
      {errors['externalId']?.map((m, i) => (
        <div key={i} className="err">{m}</div>
      ))}

      <input
        placeholder="name"
        value={name}
        onChange={(e) => setName(e.target.value)}
      />
      {errors['name']?.map((m, i) => (
        <div key={i} className="err">{m}</div>
      ))}

      <input
        placeholder="category"
        value={category}
        onChange={(e) => setCategory(e.target.value)}
      />
      {errors['category']?.map((m, i) => (
        <div key={i} className="err">{m}</div>
      ))}

      <input
        placeholder="priceGross"
        value={priceGross}
        onChange={(e) => setPriceGross(e.target.value)}
      />
      {errors['priceGross']?.map((m, i) => (
        <div key={i} className="err">{m}</div>
      ))}

      <input
        placeholder="currency"
        value={currency}
        onChange={(e) => setCurrency(e.target.value)}
      />
      {errors['currency']?.map((m, i) => (
        <div key={i} className="err">{m}</div>
      ))}

      {errors['_']?.map((m, i) => (
        <div key={i} className="err">{m}</div>
      ))}

      <button type="submit" disabled={loading}>
        {loading ? 'Creating…' : 'Create'}
      </button>
    </form>
  );
}
