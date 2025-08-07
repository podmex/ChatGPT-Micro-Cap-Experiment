import { Interface } from '@ethersproject/abi';
import { BigintIsh, Currency, CurrencyAmount, TradeType } from '@uniswap/sdk-core';
import { MethodParameters } from './utils/calldata';
import { Route } from './entities';
/**
 * Optional arguments to send to the quoter.
 */
export interface QuoteOptions {
    /**
     * The optional price limit for the trade.
     */
    sqrtPriceLimitX96?: BigintIsh;
}
/**
 * Represents the Uniswap V3 QuoterV1 contract with a method for returning the formatted
 * calldata needed to call the quoter contract.
 */
export declare abstract class SwapQuoter {
    static INTERFACE: Interface;
    /**
     * Produces the on-chain method name of the appropriate function within QuoterV2,
     * and the relevant hex encoded parameters.
     * @template TInput The input token, either Ether or an ERC-20
     * @template TOutput The output token, either Ether or an ERC-20
     * @param route The swap route, a list of pools through which a swap can occur
     * @param amount The amount of the quote, either an amount in, or an amount out
     * @param tradeType The trade type, either exact input or exact output
     * @returns The formatted calldata
     */
    static quoteCallParameters<TInput extends Currency, TOutput extends Currency>(route: Route<TInput, TOutput>, amount: CurrencyAmount<TInput | TOutput>, tradeType: TradeType, options?: QuoteOptions): MethodParameters;
}
