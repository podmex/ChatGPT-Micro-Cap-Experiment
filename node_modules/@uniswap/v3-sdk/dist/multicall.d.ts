import { Interface } from '@ethersproject/abi';
export declare abstract class Multicall {
    static INTERFACE: Interface;
    /**
     * Cannot be constructed.
     */
    private constructor();
    static encodeMulticall(calldatas: string | string[]): string;
}
