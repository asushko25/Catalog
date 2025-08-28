export interface Product {
  id: number;
  externalId?: string | null;
  name: string;
  category: string;
  priceGross: number;
  currency: string;
  createdAt: string;
}

export interface PriceResp { finalPrice: number; }
export interface ApiErrorItem { field?: string; message: string; }
