import { http } from './http';
import type { Product } from '../types/product';

type ProductRaw = Omit<Product, 'priceGross'> & { priceGross: string };

export type ProductCreate = {
  externalId?: string | null;
  name: string;
  category: string;
  priceGross: number | string;
  currency: string;
};

export async function listProducts(): Promise<Product[]> {
  const { data } = await http.get<ProductRaw[]>('/products');
  return data.map((p) => ({ ...p, priceGross: Number(p.priceGross) }));
}

export async function createProduct(payload: ProductCreate): Promise<Product> {
  const externalId =
    payload.externalId && String(payload.externalId).trim() !== ''
      ? String(payload.externalId).trim()
      : null;

  const amountNum = Number(String(payload.priceGross).replace(',', '.'));
  if (Number.isNaN(amountNum)) {
    throw { apiErrors: [{ field: 'priceGross', message: 'Invalid number' }] };
  }

  const body = {
    externalId,
    name: String(payload.name ?? '').trim(),
    category: String(payload.category ?? '').trim(),
    priceGross: amountNum.toFixed(2), // для DECIMAL — строка с 2 знаками
    currency: String(payload.currency ?? '').trim().toUpperCase(),
  };

  const { data } = await http.post<Product>('/products', body, {
    headers: { 'Content-Type': 'application/json' },
  });

  return { ...data, priceGross: Number((data as any).priceGross) } as Product;
}

// <-- ЭТО и нужно импортировать в ProductPrice.tsx
export async function getFinalPrice(id: number | string): Promise<{ finalPrice: number }> {
  const { data } = await http.get<{ finalPrice: number }>(`/products/${id}/price`);
  return data; // { finalPrice: 12.34 }
}
