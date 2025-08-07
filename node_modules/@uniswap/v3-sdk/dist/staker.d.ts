import { BigintIsh, Token } from '@uniswap/sdk-core';
import { MethodParameters } from './utils/calldata';
import { Interface } from '@ethersproject/abi';
import { Pool } from './entities';
export declare type FullWithdrawOptions = ClaimOptions & WithdrawOptions;
/**
 * Represents a unique staking program.
 */
export interface IncentiveKey {
    /**
     * The token rewarded for participating in the staking program.
     */
    rewardToken: Token;
    /**
     * The pool that the staked positions must provide in.
     */
    pool: Pool;
    /**
     * The time when the incentive program begins.
     */
    startTime: BigintIsh;
    /**
     * The time that the incentive program ends.
     */
    endTime: BigintIsh;
    /**
     * The address which receives any remaining reward tokens at `endTime`.
     */
    refundee: string;
}
/**
 * Options to specify when claiming rewards.
 */
export interface ClaimOptions {
    /**
     * The id of the NFT
     */
    tokenId: BigintIsh;
    /**
     * Address to send rewards to.
     */
    recipient: string;
    /**
     * The amount of `rewardToken` to claim. 0 claims all.
     */
    amount?: BigintIsh;
}
/**
 * Options to specify when withdrawing a position.
 */
export interface WithdrawOptions {
    /**
     * Set when withdrawing. The position will be sent to `owner` on withdraw.
     */
    owner: string;
    /**
     * Set when withdrawing. `data` is passed to `safeTransferFrom` when transferring the position from contract back to owner.
     */
    data?: string;
}
export declare abstract class Staker {
    static INTERFACE: Interface;
    protected constructor();
    private static INCENTIVE_KEY_ABI;
    /**
     *  To claim rewards, must unstake and then claim.
     * @param incentiveKey The unique identifier of a staking program.
     * @param options Options for producing the calldata to claim. Can't claim unless you unstake.
     * @returns The calldatas for 'unstakeToken' and 'claimReward'.
     */
    private static encodeClaim;
    /**
     *
     * Note:  A `tokenId` can be staked in many programs but to claim rewards and continue the program you must unstake, claim, and then restake.
     * @param incentiveKeys An IncentiveKey or array of IncentiveKeys that `tokenId` is staked in.
     * Input an array of IncentiveKeys to claim rewards for each program.
     * @param options ClaimOptions to specify tokenId, recipient, and amount wanting to collect.
     * Note that you can only specify one amount and one recipient across the various programs if you are collecting from multiple programs at once.
     * @returns
     */
    static collectRewards(incentiveKeys: IncentiveKey | IncentiveKey[], options: ClaimOptions): MethodParameters;
    /**
     *
     * @param incentiveKeys A list of incentiveKeys to unstake from. Should include all incentiveKeys (unique staking programs) that `options.tokenId` is staked in.
     * @param withdrawOptions Options for producing claim calldata and withdraw calldata. Can't withdraw without unstaking all programs for `tokenId`.
     * @returns Calldata for unstaking, claiming, and withdrawing.
     */
    static withdrawToken(incentiveKeys: IncentiveKey | IncentiveKey[], withdrawOptions: FullWithdrawOptions): MethodParameters;
    /**
     *
     * @param incentiveKeys A single IncentiveKey or array of IncentiveKeys to be encoded and used in the data parameter in `safeTransferFrom`
     * @returns An IncentiveKey as a string
     */
    static encodeDeposit(incentiveKeys: IncentiveKey | IncentiveKey[]): string;
    /**
     *
     * @param incentiveKey An `IncentiveKey` which represents a unique staking program.
     * @returns An encoded IncentiveKey to be read by ethers
     */
    private static _encodeIncentiveKey;
}
