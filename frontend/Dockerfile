FROM node:20-alpine

WORKDIR /app

# 複製 package.json 與 package-lock.json
COPY package*.json ./

# 安裝依賴
RUN npm install

# 複製整個前端專案
COPY . .

# 開發模式
CMD ["npx", "vite", "--host"]
