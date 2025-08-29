import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useMutation, useQueryClient } from '@tanstack/react-query';
import { createProduct } from '../api/products';
import type { Product } from '../types/product';
import '../styles/pages/productCreate.scss';

type FormErrors = Record<string, string[]>;

export default function ProductCreate() {
  const [externalId, setExternalId] = useState('');
  const [name, setName] = useState('');
  const [category, setCategory] = useState('');
  const [priceGross, setPriceGross] = useState('');
  const [currency, setCurrency] = useState('PLN');
  const [errors, setErrors] = useState<FormErrors>({});
  const navigate = useNavigate();
  const qc = useQueryClient();

  const createMut = useMutation({
    mutationFn: createProduct,
    onSuccess: (newProduct) => {
      qc.setQueryData<Product[]>(['products'], (old) =>
        old ? [...old, newProduct] : [newProduct]
      );
      qc.invalidateQueries({ queryKey: ['products'] });

      navigate('/');
    },
    onError: (e: any) => {
      const map: FormErrors = {};
      (e?.apiErrors ?? [{ field: '_', message: 'Unknown error' }]).forEach(
        (er: { field?: string | null; message: string }) => {
          const key = er.field || '_';
          (map[key] ||= []).push(er.message);
        }
      );
      setErrors(map);
    },
  });

  const onSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setErrors({});
    createMut.mutate({
      externalId: externalId || null,
      name,
      category,
      priceGross,
      currency,
    });
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

      <button type="submit" disabled={createMut.isPending}>
        {createMut.isPending ? 'Creating…' : 'Create'}
      </button>
    </form>
  );
}
