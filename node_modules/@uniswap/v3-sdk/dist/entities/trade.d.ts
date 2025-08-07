import { Currency, Percent, Price, CurrencyAmount, TradeType } from '@uniswap/sdk-core';
import { Pool } from './pool';
import { Route } from './route';
/**
 * Trades comparator, an extension of the input output comparator that also considers other dimensions of the trade in ranking them
 * @template TInput The input token, either Ether or an ERC-20
 * @template TOutput The output token, either Ether or an ERC-20
 * @template TTradeType The trade type, either exact input or exact output
 * @param a The first trade to compare
 * @param b The second trade to compare
 * @returns A sorted ordering for two neighboring elements in a trade array
 */
export declare function tradeComparator<TInput extends Currency, TOutput extends Currency, TTradeType extends TradeType>(a: Trade<TInput, TOutput, TTradeType>, b: Trade<TInput, TOutput, TTradeType>): number;
export interface BestTradeOptions {
    maxNumResults?: number;
    maxHops?: number;
}
/**
 * Represents a trade executed against a set of routes where some percentage of the input is
 * split across each route.
 *
 * Each route has its own set of pools. Pools can not be re-used across routes.
 *
 * Does not account for slippage, i.e., changes in price environment that can occur between
 * the time the trade is submitted and when it is executed.
 * @template TInput The input token, either Ether or an ERC-20
 * @template TOutput The output token, either Ether or an ERC-20
 * @template TTradeType The trade type, either exact input or exact output
 */
export declare class Trade<TInput extends Currency, TOutput extends Currency, TTradeType extends TradeType> {
    /**
     * @deprecated Deprecated in favor of 'swaps' property. If the trade consists of multiple routes
     * this will return an error.
     *
     * When the trade consists of just a single route, this returns the route of the trade,
     * i.e. which pools the trade goes through.
     */
    get route(): Route<TInput, TOutput>;
    /**
     * The swaps of the trade, i.e. which routes and how much is swapped in each that
     * make up the trade.
     */
    readonly swaps: {
        route: Route<TInput, TOutput>;
        inputAmount: CurrencyAmount<TInput>;
        outputAmount: CurrencyAmount<TOutput>;
    }[];
    /**
     * The type of the trade, either exact in or exact out.
     */
    readonly tradeType: TTradeType;
    /**
     * The cached result of the input amount computation
     * @private
     */
    private _inputAmount;
    /**
     * The input amount for the trade assuming no slippage.
     */
    get inputAmount(): CurrencyAmount<TInput>;
    /**
     * The cached result of the output amount computation
     * @private
     */
    private _outputAmount;
    /**
     * The output amount for the trade assuming no slippage.
     */
    get outputAmount(): CurrencyAmount<TOutput>;
    /**
     * The cached result of the computed execution price
     * @private
     */
    private _executionPrice;
    /**
     * The price expressed in terms of output amount/input amount.
     */
    get executionPrice(): Price<TInput, TOutput>;
    /**
     * The cached result of the price impact computation
     * @private
     */
    private _priceImpact;
    /**
     * Returns the percent difference between the route's mid price and the price impact
     */
    get priceImpact(): Percent;
    /**
     * Constructs an exact in trade with the given amount in and route
     * @template TInput The input token, either Ether or an ERC-20
     * @template TOutput The output token, either Ether or an ERC-20
     * @param route The route of the exact in trade
     * @param amountIn The amount being passed in
     * @returns The exact in trade
     */
    static exactIn<TInput extends Currency, TOutput extends Currency>(route: Route<TInput, TOutput>, amountIn: CurrencyAmount<TInput>): Promise<Trade<TInput, TOutput, TradeType.EXACT_INPUT>>;
    /**
     * Constructs an exact out trade with the given amount out and route
     * @template TInput The input token, either Ether or an ERC-20
     * @template TOutput The output token, either Ether or an ERC-20
     * @param route The route of the exact out trade
     * @param amountOut The amount returned by the trade
     * @returns The exact out trade
     */
    static exactOut<TInput extends Currency, TOutput extends Currency>(route: Route<TInput, TOutput>, amountOut: CurrencyAmount<TOutput>): Promise<Trade<TInput, TOutput, TradeType.EXACT_OUTPUT>>;
    /**
     * Constructs a trade by simulating swaps through the given route
     * @template TInput The input token, either Ether or an ERC-20.
     * @template TOutput The output token, either Ether or an ERC-20.
     * @template TTradeType The type of the trade, either exact in or exact out.
     * @param route route to swap through
     * @param amount the amount specified, either input or output, depending on tradeType
     * @param tradeType whether the trade is an exact input or exact output swap
     * @returns The route
     */
    static fromRoute<TInput extends Currency, TOutput extends Currency, TTradeType extends TradeType>(route: Route<TInput, TOutput>, amount: TTradeType extends TradeType.EXACT_INPUT ? CurrencyAmount<TInput> : CurrencyAmount<TOutput>, tradeType: TTradeType): Promise<Trade<TInput, TOutput, TTradeType>>;
    /**
     * Constructs a trade from routes by simulating swaps
     *
     * @template TInput The input token, either Ether or an ERC-20.
     * @template TOutput The output token, either Ether or an ERC-20.
     * @template TTradeType The type of the trade, either exact in or exact out.
     * @param routes the routes to swap through and how much of the amount should be routed through each
     * @param tradeType whether the trade is an exact input or exact output swap
     * @returns The trade
     */
    static fromRoutes<TInput extends Currency, TOutput extends Currency, TTradeType extends TradeType>(routes: {
        amount: TTradeType extends TradeType.EXACT_INPUT ? CurrencyAmount<TInput> : CurrencyAmount<TOutput>;
        route: Route<TInput, TOutput>;
    }[], tradeType: TTradeType): Promise<Trade<TInput, TOutput, TTradeType>>;
    /**
     * Creates a trade without computing the result of swapping through the route. Useful when you have simulated the trade
     * elsewhere and do not have any tick data
     * @template TInput The input token, either Ether or an ERC-20
     * @template TOutput The output token, either Ether or an ERC-20
     * @template TTradeType The type of the trade, either exact in or exact out
     * @param constructorArguments The arguments passed to the trade constructor
     * @returns The unchecked trade
     */
    static createUncheckedTrade<TInput extends Currency, TOutput extends Currency, TTradeType extends TradeType>(constructorArguments: {
        route: Route<TInput, TOutput>;
        inputAmount: CurrencyAmount<TInput>;
        outputAmount: CurrencyAmount<TOutput>;
        tradeType: TTradeType;
    }): Trade<TInput, TOutput, TTradeType>;
    /**
     * Creates a trade without computing the result of swapping through the routes. Useful when you have simulated the trade
     * elsewhere and do not have any tick data
     * @template TInput The input token, either Ether or an ERC-20
     * @template TOutput The output token, either Ether or an ERC-20
     * @template TTradeType The type of the trade, either exact in or exact out
     * @param constructorArguments The arguments passed to the trade constructor
     * @returns The unchecked trade
     */
    static createUncheckedTradeWithMultipleRoutes<TInput extends Currency, TOutput extends Currency, TTradeType extends TradeType>(constructorArguments: {
        routes: {
            route: Route<TInput, TOutput>;
            inputAmount: CurrencyAmount<TInput>;
            outputAmount: CurrencyAmount<TOutput>;
        }[];
        tradeType: TTradeType;
    }): Trade<TInput, TOutput, TTradeType>;
    /**
     * Construct a trade by passing in the pre-computed property values
     * @param routes The routes through which the trade occurs
     * @param tradeType The type of trade, exact input or exact output
     */
    private constructor();
    /**
     * Get the minimum amount that must be received from this trade for the given slippage tolerance
     * @param slippageTolerance The tolerance of unfavorable slippage from the execution price of this trade
     * @returns The amount out
     */
    minimumAmountOut(slippageTolerance: Percent, amountOut?: CurrencyAmount<TOutput>): CurrencyAmount<TOutput>;
    /**
     * Get the maximum amount in that can be spent via this trade for the given slippage tolerance
     * @param slippageTolerance The tolerance of unfavorable slippage from the execution price of this trade
     * @returns The amount in
     */
    maximumAmountIn(slippageTolerance: Percent, amountIn?: CurrencyAmount<TInput>): CurrencyAmount<TInput>;
    /**
     * Return the execution price after accounting for slippage tolerance
     * @param slippageTolerance the allowed tolerated slippage
     * @returns The execution price
     */
    worstExecutionPrice(slippageTolerance: Percent): Price<TInput, TOutput>;
    /**
     * Given a list of pools, and a fixed amount in, returns the top `maxNumResults` trades that go from an input token
     * amount to an output token, making at most `maxHops` hops.
     * Note this does not consider aggregation, as routes are linear. It's possible a better route exists by splitting
     * the amount in among multiple routes.
     * @param pools the pools to consider in finding the best trade
     * @param nextAmountIn exact amount of input currency to spend
     * @param currencyOut the desired currency out
     * @param maxNumResults maximum number of results to return
     * @param maxHops maximum number of hops a returned trade can make, e.g. 1 hop goes through a single pool
     * @param currentPools used in recursion; the current list of pools
     * @param currencyAmountIn used in recursion; the original value of the currencyAmountIn parameter
     * @param bestTrades used in recursion; the current list of best trades
     * @returns The exact in trade
     */
    static bestTradeExactIn<TInput extends Currency, TOutput extends Currency>(pools: Pool[], currencyAmountIn: CurrencyAmount<TInput>, currencyOut: TOutput, { maxNumResults, maxHops }?: BestTradeOptions, currentPools?: Pool[], nextAmountIn?: CurrencyAmount<Currency>, bestTrades?: Trade<TInput, TOutput, TradeType.EXACT_INPUT>[]): Promise<Trade<TInput, TOutput, TradeType.EXACT_INPUT>[]>;
    /**
     * similar to the above method but instead targets a fixed output amount
     * given a list of pools, and a fixed amount out, returns the top `maxNumResults` trades that go from an input token
     * to an output token amount, making at most `maxHops` hops
     * note this does not consider aggregation, as routes are linear. it's possible a better route exists by splitting
     * the amount in among multiple routes.
     * @param pools the pools to consider in finding the best trade
     * @param currencyIn the currency to spend
     * @param currencyAmountOut the desired currency amount out
     * @param nextAmountOut the exact amount of currency out
     * @param maxNumResults maximum number of results to return
     * @param maxHops maximum number of hops a returned trade can make, e.g. 1 hop goes through a single pool
     * @param currentPools used in recursion; the current list of pools
     * @param bestTrades used in recursion; the current list of best trades
     * @returns The exact out trade
     */
    static bestTradeExactOut<TInput extends Currency, TOutput extends Currency>(pools: Pool[], currencyIn: TInput, currencyAmountOut: CurrencyAmount<TOutput>, { maxNumResults, maxHops }?: BestTradeOptions, currentPools?: Pool[], nextAmountOut?: CurrencyAmount<Currency>, bestTrades?: Trade<TInput, TOutput, TradeType.EXACT_OUTPUT>[]): Promise<Trade<TInput, TOutput, TradeType.EXACT_OUTPUT>[]>;
}
