import axios from 'axios';

export const http = axios.create({
  baseURL: '/api',
  timeout: 10000,
});

http.interceptors.response.use(
  (r) => r,
  (err) => {
    const data = err?.response?.data;


    const apiErrors =
      data?.errors ??
      (Array.isArray(data?.violations)
        ? data.violations.map((v: any) => ({
            field: v.propertyPath,
            message: v.title,
          }))
        : data?.message
        ? [{ message: data.message }]
        : [{ message: 'Unknown error' }]);

    return Promise.reject({ ...err, apiErrors });
  }
);
